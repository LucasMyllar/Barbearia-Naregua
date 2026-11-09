/**
 * NA RÉGUA - AGENDAR JAVASCRIPT
 * Gerencia a seleção de serviços, data, horário e confirmação do agendamento
 */

let currentDuration = '—';
let horarioEscolhido = null;
let ultimaBusca = 0;

function formatarDuracao(minutos) {
    const h = Math.floor(minutos / 60);
    const m = minutos % 60;
    if (h === 0) return `${m} min`;
    if (m === 0) return `${h}h`;
    return `${h}h ${m}min`;
}

function formatarPreco(valor) {
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

function atualizarResumo() {
    const selecionados = document.querySelectorAll('.service-option-card.selected');
    let totalPreco = 0;
    let totalMinutos = 0;
    const nomes = [];

    selecionados.forEach(card => {
        totalPreco += Number(card.dataset.preco);
        totalMinutos += Number(card.dataset.duracao);
        nomes.push(card.dataset.nome);
    });

    const resumoServico = document.getElementById('summaryService');
    const resumoPreco = document.getElementById('summaryPrice');

    if (resumoServico) {
        resumoServico.innerText = nomes.length ? nomes.join(' + ') : 'Nenhum serviço selecionado';
    }
    if (resumoPreco) {
        resumoPreco.innerText = formatarPreco(totalPreco);
    }

    currentDuration = totalMinutos ? formatarDuracao(totalMinutos) : '—';
    updateTimeDisplay();
}

function atualizarData() {
    const pilula = document.querySelector('.date-pill.selected');
    const resumoData = document.getElementById('summaryDate');
    if (!pilula || !resumoData) return;

    const semana = pilula.querySelector('.date-pill-weekday').innerText;
    const dia = pilula.querySelector('.date-pill-day').innerText;
    const mes = pilula.querySelector('.date-pill-month').innerText;

    resumoData.innerText = `${semana}, ${dia} de ${mes}`;
}

function updateTimeDisplay() {
    const timeEl = document.getElementById('summaryTime');
    if (!timeEl) return;
    timeEl.innerText = horarioEscolhido
        ? `${horarioEscolhido} (Duração: ${currentDuration})`
        : 'Escolha um horário';
}

async function carregarHorarios() {
    const container = document.getElementById('horariosContainer');
    if (!container) return;

    horarioEscolhido = null;
    updateTimeDisplay();

    const pilula = document.querySelector('.date-pill.selected');
    const ids = [...document.querySelectorAll('.service-option-card.selected')].map(c => c.dataset.id);

    if (!pilula || ids.length === 0) {
        container.textContent = 'Escolha um serviço e uma data para ver os horários.';
        return;
    }

    const minhaVez = ++ultimaBusca;
    const url = `horarios.php?data=${pilula.dataset.data}&servicos=${ids.join(',')}`;

    try {
        const resposta = await fetch(url);
        const dados = await resposta.json();

        if (minhaVez !== ultimaBusca) return;

        if (!resposta.ok) {
            container.textContent = dados.erro || 'Não foi possível carregar os horários.';
            return;
        }
        if (dados.horarios.length === 0) {
            container.textContent = 'Sem horários disponíveis nesta data.';
            return;
        }

        const grade = document.createElement('div');
        grade.className = 'time-slots-grid';

        dados.horarios.forEach(hora => {
            const botao = document.createElement('button');
            botao.type = 'button';
            botao.className = 'time-slot-btn';
            botao.textContent = hora;
            botao.addEventListener('click', () => {
                grade.querySelectorAll('.time-slot-btn').forEach(b => b.classList.remove('selected'));
                botao.classList.add('selected');
                horarioEscolhido = hora;
                updateTimeDisplay();
            });
            grade.appendChild(botao);
        });

        container.replaceChildren(grade);
    } catch (erro) {
        if (minhaVez === ultimaBusca) {
            container.textContent = 'Não foi possível carregar os horários.';
        }
    }
}

async function confirmBooking() {
    const botao = document.querySelector('.btn-confirm-booking');
    const erroEl = document.getElementById('erroAgendamento');
    const pilula = document.querySelector('.date-pill.selected');
    const ids = [...document.querySelectorAll('.service-option-card.selected')].map(c => c.dataset.id);

    erroEl.textContent = '';

    if (ids.length === 0 || !pilula || !horarioEscolhido) {
        erroEl.textContent = 'Escolha o serviço, a data e o horário.';
        return;
    }

    const form = new FormData();
    form.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
    form.append('data', pilula.dataset.data);
    form.append('hora', horarioEscolhido);
    form.append('servicos', ids.join(','));
    
    botao.disabled = true;

    try {
        const resposta = await fetch('criar_agendamento.php', { method: 'POST', body: form });
        const dados = await resposta.json();

        if (resposta.ok) {
            document.getElementById('successModal').classList.add('active');
            carregarHorarios();
        } else {
            erroEl.textContent = dados.erro || 'Não foi possível concluir o agendamento.';
            if (resposta.status === 409) {
                carregarHorarios();
            }
        }
    } catch (erro) {
        erroEl.textContent = 'Não foi possível concluir o agendamento.';
    } finally {
        botao.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Marcar e desmarcar serviços (permite escolher vários)
    document.querySelectorAll('.service-option-card').forEach(card => {
        card.addEventListener('click', () => {
            card.classList.toggle('selected');
            atualizarResumo();
            carregarHorarios();
        });
    });

    // Escolher a data (só uma por vez)
    document.querySelectorAll('.date-pill').forEach(pilula => {
        pilula.addEventListener('click', () => {
            document.querySelectorAll('.date-pill').forEach(el => el.classList.remove('selected'));
            pilula.classList.add('selected');
            atualizarData();
            carregarHorarios();
        });
    });

    // O resumo começa com o que já está marcado
    atualizarResumo();
    atualizarData();
    carregarHorarios();

    // Fechar modal ao clicar fora do card de confirmação
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    }
});