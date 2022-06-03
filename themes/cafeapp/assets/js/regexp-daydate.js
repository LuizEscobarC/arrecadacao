const inputsValidateDay = document.querySelector('.filter_day');
if (inputsValidateDay) {
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('filter_day')) {
            let inputValue = e.target.value;
            if (inputValue < 1 || inputValue > 30 || !(/^[0-9]+$/).test(inputValue)) {
                e.target.value = null;
                alert('digite um dia do mês válido!');
                e.target.focus();
            }
        }
    });
}