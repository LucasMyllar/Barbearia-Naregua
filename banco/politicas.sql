-- =====================================================================
-- NA RÉGUA - POLÍTICAS E FUNÇÕES DO BANCO (PostgreSQL / Supabase)
-- =====================================================================
-- Este arquivo reúne tudo o que foi executado no SQL Editor do Supabase
-- além das tabelas: políticas de RLS, funções do schema app_privado e
-- privilégios do papel da aplicação.
--
-- PRÉ-REQUISITOS (não estão neste arquivo):
--   1. As cinco tabelas já criadas: usuario, servico, agendamento,
--      agendamento_servico e horario_funcionamento.
--   2. A extensão btree_gist e a restrição agendamento_sem_conflito
--      (impede sobreposição de agendamentos PENDENTE e CONFIRMADO).
--   3. O papel da aplicação, criado uma única vez com uma senha forte:
--        -- CREATE ROLE barbearia_app LOGIN PASSWORD 'troque-por-uma-senha-forte';
--      Nunca coloque a senha real neste arquivo nem no Git.
--
-- COMO USAR: execute o arquivo inteiro, de cima para baixo, no SQL
-- Editor. Ele pode ser executado de novo sem erro (usa CREATE OR REPLACE
-- e DROP POLICY IF EXISTS).
--
-- IDEIA DE SEGURANÇA: a autenticação é feita pelo PHP, então o banco não
-- sabe quem é o usuário logado no site. Por isso o papel barbearia_app
-- NÃO lê nem altera as tabelas diretamente (exceto o mínimo necessário).
-- O acesso passa por funções SECURITY DEFINER do schema app_privado, que
-- fazem só uma tarefa cada. Quem decide se a pessoa é administrador é o
-- PHP (exigirAdmin), que também envia o id do usuário vindo da sessão.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 1. ROW LEVEL SECURITY ligado nas cinco tabelas
-- ---------------------------------------------------------------------
ALTER TABLE public.usuario                ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.servico                ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.agendamento            ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.agendamento_servico    ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.horario_funcionamento  ENABLE ROW LEVEL SECURITY;


-- ---------------------------------------------------------------------
-- 2. POLÍTICAS (só o que o PHP faz diretamente nas tabelas)
-- ---------------------------------------------------------------------

-- Cadastro público: só permite criar usuários do tipo CLIENTE.
DROP POLICY IF EXISTS cadastro_publico_cliente ON public.usuario;
CREATE POLICY cadastro_publico_cliente
ON public.usuario
FOR INSERT
TO barbearia_app
WITH CHECK (tipo_usuario = 'CLIENTE');

-- Leitura do catálogo: o cliente só enxerga serviços ativos.
DROP POLICY IF EXISTS servicos_ativos_leitura ON public.servico;
CREATE POLICY servicos_ativos_leitura
ON public.servico
FOR SELECT
TO barbearia_app
USING (ativo = true);

-- Leitura dos horários de funcionamento ativos.
DROP POLICY IF EXISTS horarios_ativos_leitura ON public.horario_funcionamento;
CREATE POLICY horarios_ativos_leitura
ON public.horario_funcionamento
FOR SELECT
TO barbearia_app
USING (ativo = true);


-- ---------------------------------------------------------------------
-- 3. SCHEMA PRIVADO PARA AS FUNÇÕES
-- ---------------------------------------------------------------------
-- Fica fora do schema public para não ser exposto pela API REST do
-- Supabase. Só o papel barbearia_app recebe permissão de uso.
CREATE SCHEMA IF NOT EXISTS app_privado;
GRANT USAGE ON SCHEMA app_privado TO barbearia_app;


-- ---------------------------------------------------------------------
-- 4. FUNÇÕES: LOGIN
-- ---------------------------------------------------------------------

-- Devolve a linha de UM usuário pelo e-mail exato (inclui o hash da senha,
-- usado pelo PHP no password_verify).
CREATE OR REPLACE FUNCTION app_privado.buscar_usuario_login(p_email text)
RETURNS SETOF public.usuario
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT * FROM public.usuario WHERE email = p_email LIMIT 1;
$$;

REVOKE ALL ON FUNCTION app_privado.buscar_usuario_login(text) FROM PUBLIC, anon, authenticated;
GRANT EXECUTE ON FUNCTION app_privado.buscar_usuario_login(text) TO barbearia_app;


-- ---------------------------------------------------------------------
-- 5. FUNÇÕES: AGENDAMENTO (CLIENTE)
-- ---------------------------------------------------------------------

-- Devolve só os intervalos ocupados (PENDENTE e CONFIRMADO) de uma janela
-- de tempo, sem nenhum dado de cliente. Usada para calcular horários livres.
-- A sobreposição usa a convenção [): terminar às 09:45 e outro começar às
-- 09:45 não é conflito.
CREATE OR REPLACE FUNCTION app_privado.intervalos_ocupados(p_inicio timestamp, p_fim timestamp)
RETURNS TABLE (inicio timestamp, fim timestamp)
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT data_hora_inicio, data_hora_fim
  FROM public.agendamento
  WHERE status IN ('PENDENTE', 'CONFIRMADO')
    AND data_hora_inicio < p_fim
    AND data_hora_fim > p_inicio;
$$;

REVOKE ALL ON FUNCTION app_privado.intervalos_ocupados(timestamp, timestamp) FROM PUBLIC, anon, authenticated;
GRANT EXECUTE ON FUNCTION app_privado.intervalos_ocupados(timestamp, timestamp) TO barbearia_app;


-- Cria o agendamento e os serviços dele numa única transação. Preço e
-- duração são lidos da tabela servico (nunca vêm do navegador) e gravados
-- em preco_aplicado e duracao_aplicada. Conflito de horário é barrado pela
-- restrição agendamento_sem_conflito (erro 23P01).
CREATE OR REPLACE FUNCTION app_privado.criar_agendamento(
  p_id_cliente integer,
  p_inicio timestamp,
  p_servicos integer[],
  p_observacao text DEFAULT NULL
)
RETURNS bigint
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
DECLARE
  v_qtd integer;
  v_duracao integer;
  v_id bigint;
BEGIN
  SELECT COUNT(*), COALESCE(SUM(duracao_minutos), 0)
    INTO v_qtd, v_duracao
  FROM servico
  WHERE ativo = true AND id_servico = ANY(p_servicos);

  IF v_qtd = 0 OR v_qtd <> cardinality(p_servicos) THEN
    RAISE EXCEPTION 'servico_invalido';
  END IF;

  INSERT INTO agendamento (id_cliente, data_hora_inicio, data_hora_fim, observacao)
  VALUES (p_id_cliente, p_inicio, p_inicio + make_interval(mins => v_duracao), p_observacao)
  RETURNING id_agendamento INTO v_id;

  INSERT INTO agendamento_servico (id_agendamento, id_servico, preco_aplicado, duracao_aplicada)
  SELECT v_id, id_servico, preco, duracao_minutos
  FROM servico
  WHERE id_servico = ANY(p_servicos);

  RETURN v_id;
END;
$$;

REVOKE ALL ON FUNCTION app_privado.criar_agendamento(integer, timestamp, integer[], text) FROM PUBLIC, anon, authenticated;
GRANT EXECUTE ON FUNCTION app_privado.criar_agendamento(integer, timestamp, integer[], text) TO barbearia_app;


-- Lista os agendamentos de UM cliente, com os serviços juntos, o total e a
-- duração (valores gravados no momento da reserva).
CREATE OR REPLACE FUNCTION app_privado.meus_agendamentos(p_id_cliente integer)
RETURNS TABLE (
  id_agendamento bigint,
  inicio timestamp,
  fim timestamp,
  status text,
  servicos text,
  total numeric,
  duracao integer
)
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT a.id_agendamento::bigint,
         a.data_hora_inicio,
         a.data_hora_fim,
         a.status::text,
         string_agg(s.nome, ' + ' ORDER BY s.nome),
         SUM(x.preco_aplicado)::numeric,
         SUM(x.duracao_aplicada)::integer
  FROM public.agendamento a
  JOIN public.agendamento_servico x ON x.id_agendamento = a.id_agendamento
  JOIN public.servico s ON s.id_servico = x.id_servico
  WHERE a.id_cliente = p_id_cliente
  GROUP BY a.id_agendamento, a.data_hora_inicio, a.data_hora_fim, a.status
  ORDER BY a.data_hora_inicio;
$$;

REVOKE ALL ON FUNCTION app_privado.meus_agendamentos(integer) FROM PUBLIC, anon, authenticated;
GRANT EXECUTE ON FUNCTION app_privado.meus_agendamentos(integer) TO barbearia_app;


-- Cancela um agendamento do próprio cliente. Só cancela se estiver PENDENTE
-- ou CONFIRMADO e com pelo menos p_antecedencia_horas de antecedência.
-- Devolve true se cancelou e false se não podia cancelar. Não apaga nada.
CREATE OR REPLACE FUNCTION app_privado.cancelar_agendamento(
  p_id_cliente integer,
  p_id_agendamento bigint,
  p_antecedencia_horas integer DEFAULT 2
)
RETURNS boolean
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
DECLARE
  v_linhas integer;
BEGIN
  UPDATE agendamento
     SET status = 'CANCELADO'
   WHERE id_agendamento = p_id_agendamento
     AND id_cliente = p_id_cliente
     AND status IN ('PENDENTE', 'CONFIRMADO')
     AND data_hora_inicio > (now() AT TIME ZONE 'America/Sao_Paulo') + make_interval(hours => p_antecedencia_horas);

  GET DIAGNOSTICS v_linhas = ROW_COUNT;
  RETURN v_linhas = 1;
END;
$$;

REVOKE ALL ON FUNCTION app_privado.cancelar_agendamento(integer, bigint, integer) FROM PUBLIC, anon, authenticated;
GRANT EXECUTE ON FUNCTION app_privado.cancelar_agendamento(integer, bigint, integer) TO barbearia_app;


-- ---------------------------------------------------------------------
-- 6. FUNÇÕES: PERFIL DO CLIENTE
-- ---------------------------------------------------------------------

-- Lê nome, e-mail e telefone de UM usuário (a senha não sai por aqui).
CREATE OR REPLACE FUNCTION app_privado.meu_perfil(p_id integer)
RETURNS TABLE (nome text, email text, telefone text)
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT u.nome::text, u.email::text, u.telefone::text
  FROM public.usuario u
  WHERE u.id_usuario = p_id;
$$;

-- Altera só nome e telefone. E-mail e tipo de usuário não mudam por aqui.
CREATE OR REPLACE FUNCTION app_privado.atualizar_perfil(p_id integer, p_nome text, p_telefone text)
RETURNS boolean
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
DECLARE
  v_linhas integer;
BEGIN
  UPDATE usuario SET nome = p_nome, telefone = p_telefone WHERE id_usuario = p_id;
  GET DIAGNOSTICS v_linhas = ROW_COUNT;
  RETURN v_linhas = 1;
END;
$$;

-- Grava um novo hash de senha. A senha atual é conferida pelo PHP antes.
CREATE OR REPLACE FUNCTION app_privado.alterar_senha(p_id integer, p_hash text)
RETURNS boolean
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
DECLARE
  v_linhas integer;
BEGIN
  UPDATE usuario SET senha = p_hash WHERE id_usuario = p_id;
  GET DIAGNOSTICS v_linhas = ROW_COUNT;
  RETURN v_linhas = 1;
END;
$$;

REVOKE ALL ON FUNCTION app_privado.meu_perfil(integer) FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION app_privado.atualizar_perfil(integer, text, text) FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION app_privado.alterar_senha(integer, text) FROM PUBLIC, anon, authenticated;

GRANT EXECUTE ON FUNCTION app_privado.meu_perfil(integer) TO barbearia_app;
GRANT EXECUTE ON FUNCTION app_privado.atualizar_perfil(integer, text, text) TO barbearia_app;
GRANT EXECUTE ON FUNCTION app_privado.alterar_senha(integer, text) TO barbearia_app;


-- ---------------------------------------------------------------------
-- 7. FUNÇÕES: PAINEL DO ADMINISTRADOR
-- ---------------------------------------------------------------------
-- ATENÇÃO: o banco não sabe quem é administrador. Estas funções só podem
-- ser chamadas por arquivos PHP que começam com exigirAdmin() e conferem
-- o token CSRF.

-- Serviços: lista todos (ativos e desativados).
CREATE OR REPLACE FUNCTION app_privado.admin_listar_servicos()
RETURNS TABLE (id_servico integer, nome text, descricao text, preco numeric, duracao_minutos integer, ativo boolean)
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT s.id_servico::integer, s.nome::text, s.descricao::text, s.preco::numeric, s.duracao_minutos::integer, s.ativo
  FROM public.servico s
  ORDER BY s.ativo DESC, s.nome;
$$;

-- Serviços: cria (p_id nulo) ou edita (p_id informado). Devolve o id, ou
-- NULL se o id pedido não existir.
CREATE OR REPLACE FUNCTION app_privado.admin_salvar_servico(
  p_id integer, p_nome text, p_descricao text, p_preco numeric, p_duracao integer
)
RETURNS integer
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
DECLARE
  v_id integer;
BEGIN
  IF p_id IS NULL THEN
    INSERT INTO servico (nome, descricao, preco, duracao_minutos)
    VALUES (p_nome, p_descricao, p_preco, p_duracao)
    RETURNING id_servico INTO v_id;
  ELSE
    UPDATE servico
       SET nome = p_nome, descricao = p_descricao, preco = p_preco, duracao_minutos = p_duracao
     WHERE id_servico = p_id
    RETURNING id_servico INTO v_id;
  END IF;
  RETURN v_id;
END;
$$;

-- Serviços: ativa ou desativa (nunca apaga, para preservar o histórico).
CREATE OR REPLACE FUNCTION app_privado.admin_alterar_ativo_servico(p_id integer, p_ativo boolean)
RETURNS boolean
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
DECLARE
  v_linhas integer;
BEGIN
  UPDATE servico SET ativo = p_ativo WHERE id_servico = p_id;
  GET DIAGNOSTICS v_linhas = ROW_COUNT;
  RETURN v_linhas = 1;
END;
$$;

-- Agendamentos: lista todos, com dados do cliente e dos serviços.
CREATE OR REPLACE FUNCTION app_privado.admin_listar_agendamentos()
RETURNS TABLE (
  id_agendamento bigint, cliente text, email text, telefone text,
  inicio timestamp, fim timestamp, status text,
  servicos text, total numeric, duracao integer
)
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT a.id_agendamento::bigint,
         u.nome::text,
         u.email::text,
         u.telefone::text,
         a.data_hora_inicio,
         a.data_hora_fim,
         a.status::text,
         string_agg(s.nome, ' + ' ORDER BY s.nome),
         SUM(x.preco_aplicado)::numeric,
         SUM(x.duracao_aplicada)::integer
  FROM public.agendamento a
  JOIN public.usuario u ON u.id_usuario = a.id_cliente
  JOIN public.agendamento_servico x ON x.id_agendamento = a.id_agendamento
  JOIN public.servico s ON s.id_servico = x.id_servico
  GROUP BY a.id_agendamento, u.id_usuario
  ORDER BY a.data_hora_inicio DESC;
$$;

-- Agendamentos: só aceita as mudanças de status que fazem sentido.
--   PENDENTE   -> CONFIRMADO ou CANCELADO
--   CONFIRMADO -> CONCLUIDO  ou CANCELADO
CREATE OR REPLACE FUNCTION app_privado.admin_alterar_status_agendamento(p_id bigint, p_status text)
RETURNS boolean
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
DECLARE
  v_linhas integer;
BEGIN
  UPDATE agendamento
     SET status = p_status
   WHERE id_agendamento = p_id
     AND (
          (status = 'PENDENTE'   AND p_status IN ('CONFIRMADO', 'CANCELADO'))
       OR (status = 'CONFIRMADO' AND p_status IN ('CONCLUIDO', 'CANCELADO'))
     );

  GET DIAGNOSTICS v_linhas = ROW_COUNT;
  RETURN v_linhas = 1;
END;
$$;

-- Clientes: lista só usuários CLIENTE (sem senha), com total de
-- agendamentos e o último.
CREATE OR REPLACE FUNCTION app_privado.admin_listar_clientes()
RETURNS TABLE (
  id_usuario integer, nome text, email text, telefone text,
  total_agendamentos bigint, ultimo_agendamento timestamp
)
LANGUAGE sql
STABLE
SECURITY DEFINER
SET search_path = public
AS $$
  SELECT u.id_usuario::integer,
         u.nome::text,
         u.email::text,
         u.telefone::text,
         COUNT(a.id_agendamento),
         MAX(a.data_hora_inicio)
  FROM public.usuario u
  LEFT JOIN public.agendamento a ON a.id_cliente = u.id_usuario
  WHERE u.tipo_usuario = 'CLIENTE'
  GROUP BY u.id_usuario
  ORDER BY u.nome;
$$;

REVOKE ALL ON FUNCTION app_privado.admin_listar_servicos() FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION app_privado.admin_salvar_servico(integer, text, text, numeric, integer) FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION app_privado.admin_alterar_ativo_servico(integer, boolean) FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION app_privado.admin_listar_agendamentos() FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION app_privado.admin_alterar_status_agendamento(bigint, text) FROM PUBLIC, anon, authenticated;
REVOKE ALL ON FUNCTION app_privado.admin_listar_clientes() FROM PUBLIC, anon, authenticated;

GRANT EXECUTE ON FUNCTION app_privado.admin_listar_servicos() TO barbearia_app;
GRANT EXECUTE ON FUNCTION app_privado.admin_salvar_servico(integer, text, text, numeric, integer) TO barbearia_app;
GRANT EXECUTE ON FUNCTION app_privado.admin_alterar_ativo_servico(integer, boolean) TO barbearia_app;
GRANT EXECUTE ON FUNCTION app_privado.admin_listar_agendamentos() TO barbearia_app;
GRANT EXECUTE ON FUNCTION app_privado.admin_alterar_status_agendamento(bigint, text) TO barbearia_app;
GRANT EXECUTE ON FUNCTION app_privado.admin_listar_clientes() TO barbearia_app;


-- ---------------------------------------------------------------------
-- 8. PRIVILÉGIOS MÍNIMOS DO PAPEL DA APLICAÇÃO
-- ---------------------------------------------------------------------
-- O PHP só faz diretamente nas tabelas:
--   usuario:               INSERT e SELECT (cadastro e checagem de e-mail)
--   servico:               SELECT
--   horario_funcionamento: SELECT
-- Todo o resto passa pelas funções acima (que rodam como dono do banco).
REVOKE ALL ON public.agendamento, public.agendamento_servico FROM barbearia_app;
REVOKE UPDATE, DELETE, TRUNCATE, REFERENCES, TRIGGER ON public.usuario, public.servico, public.horario_funcionamento FROM barbearia_app;
REVOKE INSERT ON public.servico, public.horario_funcionamento FROM barbearia_app;


-- ---------------------------------------------------------------------
-- 9. CRIAR O PRIMEIRO ADMINISTRADOR (manual e controlado)
-- ---------------------------------------------------------------------
-- O cadastro público só cria CLIENTE. Para criar um administrador:
--   1. cadastre a conta normalmente pelo site;
--   2. promova pelo SQL Editor (troque o e-mail):
--
-- UPDATE usuario SET tipo_usuario = 'ADM' WHERE email = 'admin@exemplo.com';
--
--   3. saia e entre de novo (o tipo fica guardado na sessão no login).
