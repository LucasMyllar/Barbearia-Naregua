/**
 * NA RÉGUA - AGENDAR JAVASCRIPT
 * Gerencia a seleção de serviços, data, horário e confirmação do agendamento
 */

let currentDuration = '1h 15min';

function selectService(element, name, price, duration) {
    document.querySelectorAll('.service-option-card').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');

    const summaryService = document.getElementById('summaryService');
    const summaryPrice = document.getElementById('summaryPrice');
    if (summaryService) summaryService.innerText = name;
    if (summaryPrice) summaryPrice.innerText = price;

    currentDuration = duration;
    updateTimeDisplay();
}

function selectDate(element, dateStr) {
    document.querySelectorAll('.date-pill').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');

    const summaryDate = document.getElementById('summaryDate');
    if (summaryDate) summaryDate.innerText = dateStr;
}

function selectTime(element, timeStr) {
    document.querySelectorAll('.time-slot-btn').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
    updateTimeDisplay(timeStr);
}

function updateTimeDisplay(time) {
    const timeEl = document.getElementById('summaryTime');
    if (!timeEl) return;
    const selectedTime = time || document.querySelector('.time-slot-btn.selected')?.innerText || '16:00';
    timeEl.innerText = `${selectedTime} (Duração: ${currentDuration})`;
}

function confirmBooking() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.classList.add('active');
    }
}

// Fechar modal ao clicar fora do card de confirmação
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    }
});

