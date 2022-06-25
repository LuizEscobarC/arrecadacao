// FUNCTION THAT CLEAR TEMP MESSAGES
const clearMessages = () => setTimeout(() => document.querySelector('.message.animated.bounce').remove(), 10000)
// BEGIN UPDATE STATUS CLOSE/OPEN
// BEGIN CALL AJAX STATUS HOUR
const updateStatusHour = async (element) => {
    const response = await ajax(element.getAttribute('href'), {}, 'POST', 'application/json')

    if (response) {
        const status = parseInt(element.dataset.status);
        // MUDA O TEXTO DO BOTÃO
        element.textContent = (status === 1 ? 'Abrir Horário' : 'Fechar Horário');
        // MUDA A COR
        if (status === 0) {
            element.classList.add('btn_red');
            element.classList.remove('btn_green');
            element.dataset.status = 1;
        } else {
            element.classList.add('btn_green');
            element.classList.remove('btn_red');
            element.dataset.status = 0;
        }
    }
}
// END CALL AJAX STATUS HOUR


// BEGIN CALL AJAX CALC STORES
const calcStores = async (element) => {
    const ajaxResponse = document.querySelector('.ajax_response');
    const url = element.getAttribute('action');
    const data = new URLSearchParams((new FormData(element)));
    const response = await ajax(url, data, 'POST', 'application/x-www-form-urlencoded');

    if (response) {
        if (response.message) {
            ajaxResponse.insertAdjacentHTML('afterend', response.message);
            clearMessages()
        }
    }
}
// END CALL AJAX CALC STORES

// BEGIN EVENT LISTENNER CALC STORE SUBMIT
const formCalc = document.querySelector('.app_form.form_calc_store');
if (formCalc) {
    formCalc.addEventListener('submit', (e) => {
        e.preventDefault();
        calcStores(e.target);
    })
}
// END EVENT LISTENNER CALC STORE SUBMIT

// EVENT LISTENNER UPDATE STATUS
const buttons = document.querySelectorAll('.change_hour_setting');
for (let button of buttons) {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        updateStatusHour(e.target)
    });
}
// END UPDATE STATUS CLOSE/OPEN