const formMovimentGlobalVar = document.querySelector('form.app_form#moviment');

// BEGIN FUNCTIONS
async function getHours(inputSelect) {
    const select = document.querySelector('select.callback');
    const label = document.querySelector('p#label');
    const data = {};
    data[inputSelect.getAttribute('name')] = inputSelect.value;

    const callback = await fetch(inputSelect.getAttribute('rel'), {
        method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams(data)
    })
    const response = await callback.json();
    if (response) {
        label.innerHTML = response[0];
        response.shift();
        let currentHour = (document.querySelector('.current_hour' ?? null));
        if (currentHour) {
            currentHour = document.querySelector('.current_hour').value;
        }
        select.innerHTML = '<option value="">Escolha</option>';

        for (hour of response) {
            select.insertAdjacentHTML('beforeend', `<option value="${hour.id}" ${(currentHour === hour.id ? 'selected' : '')} > ${hour.description}</option>`);
        }
    }
}

async function getList(inputDataList, idHour, idStore, dateMoviment) {
    const data = {id_hour: idHour, id_store: idStore, date_moviment: dateMoviment};
    const url = inputDataList.getAttribute('rel');
    const ajaxResponse = document.querySelector('.ajax_response');

    const callback = await fetch(url, {
        method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams(data)
    });

    const response = await callback.json();

    let totalValue = null;
    let comissionValue = null;
    let netValue = null;

    if (response) {
        totalValue = parseFloat(response.total_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
        comissionValue = parseFloat(response.comission_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
        netValue = parseFloat(response.net_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
        document.querySelector("input[name='id_list']").value = parseInt(response.id)
    } else {
        ajaxResponse.innerHTML = `<div class="message info icon-info bounce animated">Não existe uma lista para a loja neste horário.</div>`;
        totalValue = 0;
        comissionValue = 0;
        netValue = 0;
        document.querySelector("input[name='id_list']").value = '';
    }

    document.querySelector('.total_value').textContent = totalValue;
    document.querySelector("input[name='total_value']").value = totalValue;
    document.querySelector('.comission_value').textContent = comissionValue;
    document.querySelector("input[name='comission_value']").value = comissionValue;
    document.querySelector('.net_value').textContent = netValue;
    document.querySelector("input[name='net_value']").value = netValue;
}

async function getStoreValueNow(inputDataList, idStore) {
    const data = {};
    lastValue = document.querySelector('.last_value');

    data['id_store'] = idStore;
    const callback = await fetch(inputDataList.dataset.url, {
        method: 'POST', body: new URLSearchParams(data)
    })

    const content = await callback.json();

    if (content) {
        lastValue.textContent = parseFloat(content.valor_saldo).toLocaleString('pt-br', {minimumFractionDigits: 2});
    }
}

async function storeVerify() {
    const flashClass = document.querySelector('.ajax_response');
    const data = new URLSearchParams((new FormData(formMovimentGlobalVar)));
    const url = document.querySelector('input.store_data_list').dataset.verify;

    const callback = await fetch(url, {
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}, method: 'POST', body: data
    })

    const response = await callback.json();

    if (response.message) {
        flashClass.insertAdjacentHTML("afterend", response.message);
    }
}

/* REMOVE ENTITY DRY FUNCTION */

async function remove(dataAttr, confirmText) {
    const selected = document.querySelector(`[data-${dataAttr}]`);
    if (selected) {
        selected.addEventListener('click', async function () {
            const remove = confirm('ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir ' + confirmText);
            const url = this.dataset[dataAttr];

            if (remove === true) {
                const callback = await fetch(url, {
                    method: 'POST', data: {}, headers: {'Content-Type': 'application/json'}
                });

                const response = await callback.json();

                if (response) {
                    if (response.scroll) {
                        window.scrollTo({top: response.scroll, behavior: 'smooth'});
                    }
                    //redirect
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                    // reload page
                    if (response.reload) {
                        window.location.reload();
                    }

                }
            }
        });
    }
}

// END FUNCTIONS

// BEGIN REMOVE ENTITIES
const dataRemoveEntities = {
    "hourremove": "esse horário?",
    "userremove": "esse usuário?",
    "centerremove": "esse centro de custo?",
    "storeremove": "essa loja?",
    "listremove": "essa lista?",
    "cashremove": "esse lançamento?",
    "movimentremove": "essa movimentação?"
}

for (keysRemove in dataRemoveEntities) {
    remove(keysRemove, dataRemoveEntities[keysRemove])
}
// END REMOVE ENTITIES

/*
* AJAX GET HOUR
*/
const hourInput = document.querySelector('input.hour');

if (hourInput) {
    hourInput.addEventListener('change', function () {
        const select = document.querySelector('select.callback');
        const label = document.getElementById('label');
        if (select || label) {
            select.textContent = '';
            label.textContent = '';
        }
        getHours(this);
    });
}

/*
* AJAX GET LIST
*/
const movimentDatas = (inputDataList, idStore) => {
    const hourSelect = document.querySelector('select.callback');
    //APAGO AS MENSAGENS REPETIDAS
    const flashClassRepeatedName = document.querySelectorAll('.message');
    if (flashClassRepeatedName.length >= 2) {
        for (element of flashClassRepeatedName) {
            element.remove();
        }
    }
    if (idStore && hourSelect.value) {
        const dateMoviment = document.querySelector("input[name='date_moviment']").value;
        getList(inputDataList, hourSelect.value, idStore, dateMoviment);
    }
}

const storeDataListInput = document.querySelector('input.store_data_list');
if (storeDataListInput) {
    storeDataListInput.addEventListener('change', function () {
        // PEGA DO DATA-LIST O ID STORE DO OPTION
        const dataValue = this.getAttribute('list');
        const storeOption = document.getElementById(dataValue).options.namedItem(this.value);
        const idStoreHidden = document.querySelector("input[name='id_store']");

        // SE EXISTIR
        if (storeOption) {
            const idStore = storeOption.dataset.id_store;
            // ADICIONA O ID NO HIDDEN INPUT
            idStoreHidden.value = idStore;
            //FAZ AS ROTINAS DE VERIFICAÇÃO E FETCH NOS DADOS NECESSÁRIOS SOMENTE PARA O MOVIMENT
            if (document.querySelector('form.app_form#moviment, .cash_flow')) {
                getStoreValueNow(this, idStore);
                if (document.querySelector('form.app_form#moviment')) {
                    //storeVerify(idStore);
                    movimentDatas(this, idStore)
                }
            }
        }

    });
}

const callback = document.querySelector('select.callback');
if (callback) {
    callback.addEventListener('change', function () {
        movimentDatas(null)
    });
}

/*
* VALOR RECOLHIDO CALCULO
*/

// BEGIN MOVIMENTS CALCS
function calc(value, $this) {
    let input = document.querySelector("input[name='" + value + "']");
    //Case o input esteja vazio, para não retornar NaN
    if (input.value) {
        // VALOR DESPESAS
        const expense = parseFloat($this.value.replaceAll('.', '').replace(',', '.'));
        // VALOR DINHEIRO
        const paying = parseFloat(input.value.replaceAll('.', '').replace(',', '.'));
        /* Valor reclohido + despesas*/

        // VALOR RECOLHIDO
        const getValue = (paying + expense);
        // VALOR RECOLHIDO EM BRL
        const getValueBr = getValue.toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        /* Valor a acertar é o valor líquido da lista*/
        // VALOR LÍQUIDO | VALOR ACERTAR
        const netValue = document.querySelector('p.net_value').textContent;
        /* Ao final o saldo anterior e o saldo atual que é a mesma coisa, recebe o novo saldo do calculo*/
        // VALOR ANTERIOR | SALDO ATUAL
        let last_val = null;
        if (document.querySelector('.app_form.edit')) {
            document.querySelector('p.last_value').textContent = document.querySelector("input[name='last_value']").value;
            last_val = document.querySelector('p.last_value').textContent;
        } else {
           last_val = document.querySelector('p.last_value').textContent
        }

        document.querySelector("input[name='last_value']").value = last_val.toLocaleString('pt-br', {
            minimumFractionDigits: 2, maximumFractionDigits: 2
        });

        document.querySelector("input[name='net_value']").value = netValue;
        document.querySelector("input[name='get_value']").value = getValueBr;
        document.querySelector('.get_value').innerHTML = getValueBr;

        if (last_val && netValue) {
            // É o valor que tem que ser abatido  com o valor recolhido + o valor de despesas
            // VALOR A ACERTAR | VALOR LIQUIDO
            const beatValue = (getValue - parseFloat(netValue.replaceAll('.', '')
                .replace(',', '.')));

            // NOVO VALOR ATUAL | SALDO ANTERIOR
            const newValue = (parseFloat(last_val.replaceAll('.', '')
                .replace(',', '.')) + beatValue)
                .toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            const beatValueBrl = beatValue.toLocaleString('pt-br', {
                minimumFractionDigits: 2, maximumFractionDigits: 2
            });
            document.querySelector('p.beat_value').innerHTML = beatValueBrl;
            document.querySelector('.new_value').innerHTML = newValue;
            document.querySelector("input[name='beat_value']").value = beatValueBrl;
            document.querySelector("input[name='new_value']").value = newValue;

        } else {
            alert('Nome da Loja e Horário são necessários!');
        }
    }
}

const inputMoviment = document.querySelector("form.app_form#moviment");
if (inputMoviment) {
    document.querySelector("input[name='expend']").addEventListener('keyup', function () {
        calc('paying_now', this);
    });

    document.querySelector("input[name='paying_now']").addEventListener('keyup', function () {
        calc('paying_now', document.querySelector("input[name='expend']"));
    });
}

/* Não envia o formulário informando se quer ou não adicionar um prémio, após a escolha clicando novamente o
formulário é enviado*/
if (formMovimentGlobalVar) {
    formMovimentGlobalVar.addEventListener('submit', function (e) {
        e.preventDefault();
        //realiza os calculos para caso os valores não sejam typados
        const $input = document.querySelector("input[name='expend']");
        calc('paying_now', $input);
        let inputPrize = document.querySelector("input[name='prize']").value;
        if (inputPrize) {
            inputPrize = parseFloat(inputPrize.replaceAll('.', '').replace(',', '.'));
            // VALOR DESPESAS
            const expense = parseFloat($input.value.replaceAll('.', '').replace(',', '.'));
            // VALOR DINHEIRO
            const paying = parseFloat(document.querySelector("input[name='paying_now']").value.replaceAll('.', '').replace(',', '.'));
            /* Valor reclohido + despesas*/
            // VALOR RECOLHIDO
            const getValue = (paying + expense);
            /* Valor a acertar é o valor líquido da lista*/
            // VALOR LÍQUIDO | VALOR ACERTAR
            const netValue = document.querySelector('p.net_value').textContent;
            /* Ao final o saldo anterior e o saldo atual que é a mesma coisa, recebe o novo saldo do calculo*/
            // VALOR ANTERIOR | SALDO ATUAL
            const last_val = document.querySelector('p.last_value');
            // VALOR A ACERTAR | VALOR LIQUIDO
            const beatValue = (getValue - parseFloat(netValue.replaceAll('.', '').replace(',', '.')));
            // NOVO VALOR ATUAL | SALDO ANTERIOR
            const storeNewValue = (parseFloat(last_val.textContent.replaceAll('.', '').replace(',', '.')) + beatValue);

            if (storeNewValue < 0) {
                const negativeValue = window.confirm('Deseja abater o saldo da loja?');
                if (negativeValue) {
                    const storeValuePositive = Math.abs(storeNewValue);
                    let prizeOffice = null;
                    let prizeStore = null;
                    let beatPrize = null;
                    let newStoreValue = null;

                    if (storeValuePositive < inputPrize) {
                        prizeOffice = inputPrize - storeValuePositive;
                        prizeStore = storeValuePositive;
                        beatPrize = storeValuePositive;
                        newStoreValue = 0;

                        // VERIFICA SE O VALOR DO ESCRITÓRIO TEM CENTAVOS E SE SIM APLICA AO NOVO VALOR DA LOJA
                        prizeOfficeToString = prizeOffice.toFixed(2).toString();
                        const hasCents = prizeOfficeToString.search(/.([1-9]+)/);
                        if (hasCents !== -1) {
                            let cents = prizeOfficeToString.slice(-2);
                            let newOfficePrizePay = prizeOfficeToString.slice(0, hasCents);
                            prizeOffice = parseFloat(newOfficePrizePay);
                            newStoreValue = parseFloat('0.' + cents);
                        }

                    } else if (storeValuePositive > inputPrize) {
                        prizeOffice = 0;
                        prizeStore = inputPrize;
                        beatPrize = inputPrize;
                        newStoreValue = -Math.abs((storeValuePositive - inputPrize));
                    } else {
                        prizeOffice = 0;
                        prizeStore = inputPrize;
                        beatPrize = inputPrize;
                        newStoreValue = 0;
                    }

                    const newStoreValueBrl = newStoreValue.toLocaleString('pt-br', {
                        minimumFractionDigits: 2, maximumFractionDigits: 2
                    });

                    const beatPrizeBrl = beatPrize.toLocaleString('pt-br', {
                        minimumFractionDigits: 2, maximumFractionDigits: 2
                    });

                    const prizeOfficeBrl = prizeOffice.toLocaleString('pt-br', {
                        minimumFractionDigits: 2, maximumFractionDigits: 2
                    });

                    const prizeStoreBrl = prizeStore.toLocaleString('pt-br', {
                        minimumFractionDigits: 2, maximumFractionDigits: 2
                    });

                    // SE TIVER CENTAVOS NO PAGAMENTO DE PREMIO DO ESCRITÓRIO
                    // if (prizeOfficeBrl)

                    document.getElementsByClassName('new_value').textContent = newStoreValueBrl;
                    document.querySelector("input[name='new_value']").textContent = newStoreValueBrl;

                    document.querySelector('label.prize_output').insertAdjacentHTML('afterbegin', `<span class="field icon-leanpub">Valor de Abate Premio:</span>
                            <p class="app_widget_title beat_prize">${beatPrize}</p>
                            <input type="hidden" name="beat_prize" value="${beatPrizeBrl}">
                            <input type="hidden" name="prize_office" value="${prizeOfficeBrl}">
                            <input type="hidden" name="prize_store" value="${prizeStoreBrl}">`);
                    alert(`A loja pagará: R$${prizeStoreBrl}.
                     O escritório pagará: R$${prizeOfficeBrl}.`);
                } else {

                    const beatPrizeBrl = inputPrize.toLocaleString('pt-br', {
                        minimumFractionDigits: 2, maximumFractionDigits: 2
                    });

                    document.querySelector('label.prize_output').insertAdjacentHTML('afterbegin', `<span class="field icon-leanpub">Valor de Abate Premio:</span>
                            <input type="hidden" name="beat_prize" value="0">
                            <input type="hidden" name="prize_office" value="${beatPrizeBrl}">
                            <input type="hidden" name="prize_store" value="0">`);
                    alert(`<h1>O escritório pagará: R$${beatPrizeBrl}.</h1>`);
                }
            } else {
                inputPrize = inputPrize.toLocaleString('pt-br', {
                    minimumFractionDigits: 2, maximumFractionDigits: 2
                });
                document.querySelector('label.prize_output').insertAdjacentHTML('afterbegin', `<span class="field icon-leanpub">Sem abate</span>
                            <input type="hidden" name="beat_prize" value="0">
                            <input type="hidden" name="prize_office" value="${inputPrize}">
                            <input type="hidden" name="prize_store" value="0">`);
            }
            setTimeout(function () {
            }, 1000);
        }
        const formMoviment = formMovimentGlobalVar;
        formSub(formMoviment);
    });
}
// END MOVIMENT CALCS
0
// BEGIN COMO DEFAULT ELE SETA OS INPUTS DE DATA DOS CADASTROS COM A DATA ATUAL
if (window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-lista' ||
    window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-fluxo-de-caixa' ||
    window.location.toString() === 'http://www.localhost/arrecadacao/app/cadastrar-lista' ||
    window.location.toString() === 'http://www.localhost/arrecadacao/app/cadastrar-fluxo-de-caixa' ||
    window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-movimentacao' ||
    window.location.toString() === 'http://www.localhost/arrecadacao/app/cadastrar-movimentacao') {
    const data = new Date();
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    dataAtual = ano + '-' + mes + '-' + dia;
    const hourInput = document.querySelector('input.hour');
    hourInput.setAttribute('value', dataAtual);
    getHours(hourInput)
}
// END DEFAULT HOUR

//ANIMATED HOME WITH TOGGLE GRADIENT
if (document.querySelector('article.app_flex')) {
    tradeColor()

    function tradeColor() {
        const color = document.querySelector('article.app_flex');
        color.classList.add('transition-lower', 'gradient', 'radius');
        color.classList.toggle('gradient-hover-self')

        setTimeout(function () {
            tradeColor()
        }, 1000);
    }
}
//END ANIMATED HOME

// BEGIN JQUERY ONLY
$(function () {
    /* Select with search*/
    $("select.select2Input").select2({
        width: '100%'
    });

    /*
     * jQuery MASK
     */
    $(".mask-money-negative").mask('N0N0N0N.N0N0N0N.N0N0N0N.N0N0N0N.N0N0N0N,N0N0N', {
        translation: {
            'N': {
                pattern: /[-]/, optional: true
            }
        }, reverse: true, placeholder: '0,00'
    });
    $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
    $(".mask-date").mask('00/00/0000', {reverse: true});
    $(".mask-month").mask('00/0000', {reverse: true});
    $(".mask-doc").mask('000.000.000-00', {reverse: true});
    $(".mask-day").mask('00', {reverse: true});
});


if (document.querySelector('.app_form#moviment')) {
    const parentModal = document.querySelector('.app_modal');

    // ABRE E FECHA MODAL DE CALCULADORA
    document.addEventListener('keyup', function (e) {
        const modal = document.querySelector('.app_modal_calc');
        if (e.key === 'Control') {
            parentModal.style.display = 'flex';
            modal.style.display = 'block';
            parentModal.dataset.modalclose = 'false';
            // FAZ FOCAR NO INPUT
            modal.children[1].children[0].children[1].focus();
            // REALIZA OS CALCULOS
            const formCalc = document.querySelector('.app_form.ajax_off');
            const inputCalc = document.querySelector('.input_calc');
            const currentResult = document.querySelector('.current_result');
            let currentValue = currentResult.textContent;

            formCalc.addEventListener('submit', (e) => {
                e.preventDefault();
            })

            inputCalc.addEventListener('keyup', () => {
                if (currentValue) {
                    currentValue = parseFloat(currentValue.value.replaceAll('.', '').replace(',', '.'));
                    currentValue += inputCalc.value;
                }
                currentResult.textContent = currentValue.toLocaleString('pt-br', {
                    maximumFractionDigits: 2,
                    minimumFractionDigits: 2
                });
            })
        }

        if (e.key === 'Escape') {
            modal.style.display = 'none';
            parentModal.style.display = 'none';
            parentModal.dataset.modalclose = 'true';
        }
    })

    // ABRE E FECHA MODAL DE CALCULADORA
    document.addEventListener('keyup', function (e) {
        const modal = document.querySelector('.app_modal_moviment');
        if (e.key === 'i') {
            parentModal.style.display = 'flex';
            modal.style.display = 'block';
            parentModal.dataset.modalclose = 'false';
            // FAZ FOCAR NO INPUT
        }

        if (e.key === 'Escape') {
            modal.style.display = 'none';
            parentModal.style.display = 'none';
            parentModal.dataset.modalclose = 'true';
        }
    })

}

// CASH FLOW DEBIT STORE
let hasStore = document.querySelector('.store_data_list.cash_flow');
if (hasStore) {
    const formCashFlow = hasStore.parentElement.parentElement.parentElement;
    formCashFlow.addEventListener('submit', (e) => {
        e.preventDefault();

        let storeNewValue = parseFloat(document.querySelector('.last_value').textContent.replaceAll('.', '').replace(',', '.'));
        let isDebtChecked = document.querySelector('input[name="type"]:checked').value;
        let inputValueCash = parseFloat(document.querySelector('.cash_value').value.replaceAll('.', '').replace(',', '.'));

        if (isDebtChecked && (isDebtChecked === '2') && (storeNewValue < 0)) {
            const negativeValue = window.confirm('Deseja abater o saldo da loja?');
            if (negativeValue) {
                // TORNA POSITIVO PARA OS CALCULOS
                const storeValuePositive = Math.abs(storeNewValue);
                let valueOfficeToPay = null;
                let beatValue = null;
                let newStoreValue = null;

                if (storeValuePositive < inputValueCash) {
                    valueOfficeToPay = inputValueCash - storeValuePositive;
                    beatValue = storeValuePositive;
                    newStoreValue = 0;

                    // VERIFICA SE O VALOR DO ESCRITÓRIO TEM CENTAVOS E SE SIM APLICA AO NOVO VALOR DA LOJA
                    valueCentsString = valueOfficeToPay.toFixed(2).toString();
                    const hasCents = valueCentsString.search(/.([1-9]+)/);
                    if (hasCents !== -1) {
                        const cents = valueCentsString.slice(-2);
                        newStoreValue = parseFloat('0.' + cents);

                        valueOfficeToPay = parseFloat(valueCentsString.slice(0, hasCents));
                    }

                } else if (storeValuePositive > inputValueCash) {
                    valueOfficeToPay = 0;
                    beatValue = inputValueCash;
                    newStoreValue = -Math.abs((storeValuePositive - inputValueCash));
                } else {
                    valueOfficeToPay = 0;
                    beatValue = inputValueCash;
                    newStoreValue = 0;
                }

                const newStoreValueBrl = newStoreValue.toLocaleString('pt-br', {
                    minimumFractionDigits: 2, maximumFractionDigits: 2
                });
                const beatValueBrl = beatValue.toLocaleString('pt-br', {
                    minimumFractionDigits: 2, maximumFractionDigits: 2
                });
                const valueOfficeToPayBrl = valueOfficeToPay.toLocaleString('pt-br', {
                    minimumFractionDigits: 2, maximumFractionDigits: 2
                });

                // SE TIVER CENTAVOS NO PAGAMENTO DE PREMIO DO ESCRITÓRIO
                // if (prizeOfficeBrl)

                document.getElementsByClassName('last_value').textContent = newStoreValueBrl;
                document.querySelector("input[name='last_value']").value = newStoreValueBrl;

                document.querySelector('label.prize_output').insertAdjacentHTML('afterbegin', `<span class="field icon-leanpub">Valor de Abate:</span>
                            <p class="app_widget_title beat_value_store">${beatValueBrl}</p>
                            <input type="hidden" name="beat_store" value="${beatValueBrl}">
                            <input type="hidden" name="value_office_to_pay" value="${valueOfficeToPayBrl}">`);
                alert(`Foi abatido do saldo da loja: R$${beatValueBrl}.
                     O escritório pagará em dinheiro: R$${valueOfficeToPayBrl}.`);
            }
        } else {
            inputValueCashBrl = inputValueCash.toLocaleString('pt-br', {
                minimumFractionDigits: 2, maximumFractionDigits: 2
            });
            document.querySelector('label.prize_output').insertAdjacentHTML('afterbegin', `<span class="field icon-leanpub">Sem abate</span>
                            <input type="hidden" name="beat_prize" value="0">
                            <input type="hidden" name="prize_office" value="${inputValueCashBrl}">
                            <input type="hidden" name="prize_store" value="0">`);
        }
        setTimeout(function () {
        }, 1000);

        // após tudo envio o formulário
        formSub(formCashFlow);
    })
}
