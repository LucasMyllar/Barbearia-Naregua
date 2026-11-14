# Na Régua — Sistema de Agendamento para Barbearia

Sistema web para uma barbearia: o **cliente** agenda horários online e acompanha seus agendamentos, e o **administrador** gerencia serviços, agenda e clientes.

Projeto acadêmico desenvolvido por **Lucas Myllar** — Análise e Desenvolvimento de Sistemas, **UNIBRA - Centro Universitário Brasileiro**, **[2026/3º Semestre]**.

Repositório: https://github.com/LucasMyllar/Barbearia-Naregua

---

## Funcionalidades

### Cliente
- Criar conta e fazer login
- Escolher **um ou mais serviços** (preço e duração somados)
- Escolher a data e um **horário livre** (calculado em tempo real)
- Ver seus agendamentos por abas: próximos, histórico e cancelados
- Cancelar um agendamento próprio (até 2 horas antes)
- Ver e editar nome e telefone, e trocar a senha

### Administrador
- Dashboard com os números do dia
- Listar todos os agendamentos e mudar o status (confirmar, concluir, cancelar)
- Cadastrar, editar, ativar e desativar serviços
- Consultar a base de clientes

---

## Tecnologias

| Camada | Tecnologia |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8 com PDO |
| Banco de dados | PostgreSQL hospedado no Supabase (Session Pooler) |
| Dependências | Composer, `vlucas/phpdotenv` |
| Ambiente local | XAMPP |
| Versionamento | Git e GitHub |

---

## Estrutura de pastas

```text
naregua/
├── assets/
│   ├── css/            estilos de cada tela
│   └── js/             scripts de cada tela
├── banco/
│   └── politicas.sql   políticas RLS, funções e privilégios do banco
├── config/
│   └── database.php    conexão PDO (lê as credenciais do .env)
├── includes/
│   ├── auth.php        sessão, exigirLogin() e exigirAdmin()
│   ├── agenda.php      cálculo dos horários livres
│   └── helpers.php     funções de formatação usadas no painel admin
├── paginas/            telas e endpoints PHP
├── .env                credenciais (NÃO vai para o Git)
├── .gitignore
├── composer.json
└── README.md
```

---

## Como rodar localmente

### Pré-requisitos
- XAMPP com PHP 8 e as extensões `pdo_pgsql` e `pgsql` habilitadas no `php.ini`
- Composer
- Um projeto no Supabase (plano gratuito serve)

### Passo a passo

1. **Clone o projeto** dentro da pasta pública do XAMPP:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/LucasMyllar/Barbearia-Naregua.git naregua
   cd naregua
   ```

2. **Instale as dependências:**
   ```bash
   composer install
   ```

3. **Crie o banco no Supabase.** Crie as cinco tabelas (veja "Modelo de dados" abaixo), a extensão `btree_gist` e a restrição `agendamento_sem_conflito`. Crie também o papel da aplicação, com uma senha forte (veja o cabeçalho de `banco/politicas.sql`).

4. **Execute `banco/politicas.sql`** inteiro no SQL Editor do Supabase.

5. **Crie o arquivo `.env`** na raiz, com os dados do **Session Pooler** do Supabase:
   ```dotenv
   DB_HOST=aws-0-<regiao>.pooler.supabase.com
   DB_PORT=5432
   DB_NAME=postgres
   DB_USER=barbearia_app.<ref-do-projeto>
   DB_PASSWORD=<senha-do-papel-barbearia_app>
   ```
   Nunca envie esse arquivo ao Git (ele já está no `.gitignore`).

6. **Cadastre os serviços e os horários de funcionamento.** A tabela `horario_funcionamento` usa `dia_semana` de 1 (segunda) a 6 (sábado). O domingo não tem registro, então é tratado como fechado.

7. **Abra** `http://localhost/naregua/paginas/index.php`.

8. **Crie o primeiro administrador:** cadastre uma conta pelo site e promova pelo SQL Editor (instruções no final do `banco/politicas.sql`). Saia e entre de novo.

---

## Modelo de dados

| Tabela | Função |
|---|---|
| `usuario` | Clientes e administradores (`tipo_usuario`: `CLIENTE` ou `ADM`) |
| `servico` | Catálogo de serviços (preço, duração em minutos, `ativo`) |
| `agendamento` | Reservas (início, fim, status: `PENDENTE`, `CONFIRMADO`, `CONCLUIDO`, `CANCELADO`) |
| `agendamento_servico` | Serviços de cada agendamento, com `preco_aplicado` e `duracao_aplicada` |
| `horario_funcionamento` | Faixas de funcionamento por dia da semana |

O preço e a duração são **copiados no momento da reserva**, então mudar um serviço depois não altera agendamentos antigos. Serviços **nunca são apagados**, só desativados, para preservar o histórico.

---

## Regras de negócio

- O preço total e a duração total são a soma dos serviços escolhidos, e o horário de término é calculado automaticamente.
- Só aparecem horários que **cabem inteiros** dentro de uma faixa de funcionamento, de 30 em 30 minutos, e que ainda **não passaram**.
- Agendamentos `PENDENTE` e `CONFIRMADO` ocupam o horário. `CANCELADO` libera, e `CONCLUIDO` não bloqueia novos horários.
- Um atendimento pode começar exatamente quando outro termina (intervalos `[)`).
- Sobreposição é impedida **no banco** pela restrição `agendamento_sem_conflito` (GiST com `btree_gist`), mesmo se duas pessoas tentarem o mesmo horário ao mesmo tempo.
- O cliente só vê e cancela os próprios agendamentos, até 2 horas antes.
- O administrador só pode fazer transições de status válidas: `PENDENTE` vira `CONFIRMADO` ou `CANCELADO`, e `CONFIRMADO` vira `CONCLUIDO` ou `CANCELADO`.

---

## Segurança

- **SQL Injection:** todas as consultas usam `prepare()` e `execute()` do PDO.
- **Senhas:** `password_hash()` e `password_verify()`. O hash nunca é exibido e nunca vai para a sessão.
- **Sessão:** `session_regenerate_id(true)` no login, cookie `httponly` e `samesite`, `use_strict_mode`, e destruição completa no logout.
- **CSRF:** token em todos os formulários e endpoints que alteram dados, conferido com `hash_equals()`.
- **XSS:** toda saída vinda do banco ou do usuário passa por `htmlspecialchars()` (ou `textContent` no JavaScript).
- **Controle de acesso:** `exigirLogin()` e `exigirAdmin()` no topo de todas as páginas e endpoints internos.
- **Cadastro público** cria somente `CLIENTE`, e isso é reforçado também por uma política do banco. Administradores são criados manualmente, nunca pelo formulário.
- **Validação no backend:** todos os dados são conferidos de novo no PHP. Preço, duração e id do cliente **nunca** vêm do navegador: o PHP lê do banco ou da sessão.
- **Credenciais** só no `.env`, fora do Git.

### Arquitetura de acesso ao banco

A autenticação é própria (PHP + tabela `usuario`), e não o Supabase Auth. Por isso o PostgreSQL não sabe qual usuário do site está logado, e políticas RLS por usuário não seriam uma barreira confiável.

A solução adotada:

1. O **RLS está ligado** em todas as tabelas.
2. O papel `barbearia_app`, usado pela aplicação, tem privilégios mínimos: só os `INSERT` e `SELECT` estritamente necessários, com políticas restritas (cadastro só de `CLIENTE`, leitura só de serviços e horários ativos).
3. Todo o resto passa por **funções `SECURITY DEFINER` no schema `app_privado`**, que não é exposto pela API pública do Supabase. Cada função faz uma única tarefa e recebe o id do usuário que o PHP leu da sessão.

Assim, mesmo que exista uma falha no PHP, o papel da aplicação não consegue listar, alterar ou apagar dados fora dessas funções.

---

## Limitações conhecidas e melhorias futuras

- Não há limite de tentativas de login.
- O cadastro não confirma o e-mail, e não existe recuperação de senha.
- O administrador não cadastra agendamentos presenciais pelo painel (todo agendamento precisa de um cliente cadastrado).
- Não há reagendamento, cadastro de barbeiros nem avaliações.
- Os horários de funcionamento só são alterados direto no banco.
- Para publicar online: usar HTTPS, desligar `display_errors`, apontar a pasta pública do servidor só para o que deve ser acessível e guardar as credenciais em variáveis de ambiente do servidor.
