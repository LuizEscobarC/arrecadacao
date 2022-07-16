const cashFlowForm = document.querySelector('.app_form.cash_flow');
const urlCashFlow = cashFlowForm.getAttribute('action');
// MESSAGE NODES
const flashClassCashFlow = "ajax_response";
const flashCashFlow = document.querySelector("." + flashClassCashFlow);

if (cashFlowForm) {
    cashFlowForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formDataCashFlow = new FormData(cashFlowForm);
        const lastValue = toAppNumber(formDataCashFlow.get('last_value'));
        const typeOperation = formDataCashFlow.get('type');
        const value = toAppNumber(formDataCashFlow.get('value'));

        if (lastValue < 0 && typeOperation === '2' && value !== 0) {
            // INVERTE O VALOR PARA OS CALCULOS
            const beat = confirm('Deseja abater no saldo da loja?');
            formDataCashFlow.set('beat', ((beat) ? 1 : 0));

        }

        ajax(urlCashFlow, new URLSearchParams(formDataCashFlow), 'POST', 'application/x-www-form-urlencoded').then(response => {
            if (response.success) {
                flashCashFlow.innerHTML = response.success.message;
                flashCashFlow.style.display = 'flex';
                flashCashFlow.classList.add('bounce', 'animated');
                if (response.office_expense !== '' && response.store_expense !== '') {
                    document.querySelector('.store_expense').textContent = toBrNumber(response.store_expense);
                    document.querySelector('.office_expense').textContent = toBrNumber(response.office_expense);
                    if (response.beat) {
                        alert(`A loja pagará: R$${toBrNumber(response.store_expense)} e o escritório pagará: R$${toBrNumber(response.office_expense)}`)
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 300)
                }

            }

            if (response.error) {
                flashCashFlow.innerHTML = response.error.message;
                flashCashFlow.style.display = 'flex';
                flashCashFlow.classList.add('bounce', 'animated');
            }
        })

    });
}
