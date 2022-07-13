const cashFlowForm = document.querySelector('.app_form.cash_flow');
if (cashFlowForm) {
    cashFlowForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formDataCashFlow = new FormData(cashFlowForm);
        const lastValue = toAppNumber(formDataCashFlow.get('last_value'));
        const typeOperation = formDataCashFlow.get('type');
        const value = toAppNumber(formDataCashFlow.get('value'));
        console.log(lastValue, typeOperation)

        if (lastValue < 0 && typeOperation === '2' && value !== 0) {
            // INVERTE O VALOR PARA OS CALCULOS
            const lastValueInverted = Math.abs(lastValue);
            const beat = confirm('Deseja abater no saldo da loja?');

            if (beat) {
                if (lastValueInverted > value) {
                    const expenseOffice = 0;
                    const expenseStore = value - lastValueInverted;
                }

                if (lastValueInverted <= value) {
                    const expenseStore = lastValueInverted;
                    const expenseOffice = (value - lastValueInverted);
                    const newValue = 0;
                }
                const beatValue = 0;
            }

            if (!beat) {
                const expenseStore = 0;
                const expenseOffice = value;
                const newValue = lastValue;
            }
        }

        if (lastValue > 0 && typeOperation === '2' && value !== 0) {
            const expenseStore = 0;
            const expenseOffice = value;
            const newValue = lastValue;
        }

    });
}
