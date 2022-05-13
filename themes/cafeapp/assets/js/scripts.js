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


// BEGIN GET MOVIMENT
async function getMoviment(data) {
    const url = document.querySelector('.app_form#moviment').dataset.getmoviment;
    const callback = await fetch(url, {
        method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams(data)
    });
    return await callback.json().then((response) => {
        if (response) {
            let link = response.link;
            document.querySelector('.app_form#moviment').insertAdjacentHTML('afterbegin', `
            <label class="link_current_moviment">
                <p class="app_widget_title padding_btn gradient gradient-blue gradient-hover radius transition">
                    <a class="desc moviment color_white" style="text-decoration: none;"
                       href="${link}">Clique para ir a página de edição do movimento Atual.</a></p>
            </label>
       `);
            const selector = (selector) => document.querySelector('.' + selector);

            selector('date_moviment_view').textContent = response.moviment.date_moviment;
            selector('week_day_view').textContent = response.moviment.hour.week_day;
            selector('hour_description_view').textContent = response.moviment.hour.description;
            selector('store_name_view').textContent = response.moviment.store.nome_loja;
            selector('saldo_atual_view').textContent = response.moviment.store.valor_saldo;
            selector('last_value_view').textContent = response.moviment.last_value;
            selector('total_value_view').textContent = response.moviment.list.total_value;
            selector('comission_value_view').textContent = response.moviment.list.comission_value;
            selector('net_value_view').textContent = response.moviment.list.net_value;
            selector('paying_now_view').textContent = response.moviment.paying_now;
            selector('expend_view').textContent = response.moviment.expend;
            selector('get_value_view').textContent = response.moviment.get_value;
            selector('beat_value_view').textContent = response.moviment.beat_value;
            selector('new_value_view').textContent = response.moviment.new_value;
            selector('prize_value_view').textContent = response.moviment.prize;
            selector('beat_prize_view').textContent = response.moviment.beat_prize;
            selector('prize_office_view').textContent = response.moviment.prize_office;
            selector('prize_store_view').textContent = response.moviment.prize_store;


            return true;
        } else {
            return false;
        }
    });
}

// END GET MOVIMENT

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

    // SE TIVER LINK REMOVE NAO IMPORTA SE EXISTE LISTA OU NAO
    const hasLink = document.querySelectorAll('.link_current_moviment');
    if (hasLink) {
        for (link of hasLink) {
            link.remove();
        }
    }

    // SE HOUVER RETORNO DE LISTA BY DATA, LOJA E HORARIO
    if (response) {
        totalValue = parseFloat(response.total_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
        comissionValue = parseFloat(response.comission_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
        netValue = parseFloat(response.net_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
        document.querySelector("input[name='id_list']").value = parseInt(response.id)
    } else {
        // JÁ EXISTE UM LANÇAMENTO
        const movimentExists = await getMoviment(data);
        if (movimentExists) {
            ajaxResponse.innerHTML = `<div class="message success icon-info bounce animated">Os valores das listas foram zerados pois já foram calculados nesse horário.</div>`;
        } else {
            ajaxResponse.innerHTML = `<div class="message info icon-info bounce animated">Não existe uma lista para a loja neste horário.</div>`;
        }
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

// END FUNCTIONS

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
    const dataValue = storeDataListInput.getAttribute('list');
    const callback = document.querySelector('select.callback');
    if (callback) {
        callback.addEventListener('change', function () {
            if (storeDataListInput.value) {
                const storeOption = document.getElementById(dataValue).options.namedItem(storeDataListInput.value);
                const idStoreHidden = document.querySelector("input[name='id_store']");
                if (storeOption) {
                    const idStore = storeOption.dataset.id_store;
                    // ADICIONA O ID NO HIDDEN INPUT
                    idStoreHidden.value = idStore;
                    movimentDatas(storeDataListInput, idStore)
                }
            }
        });

        if (window.location.toString().indexOf('movimentacao')) {
            if (storeDataListInput.value) {
                const storeOption = document.getElementById(dataValue).options.namedItem(storeDataListInput.value);
                const idStoreHidden = document.querySelector("input[name='id_store']");

                if (storeOption) {
                    const idStore = storeOption.dataset.id_store;
                    // ADICIONA O ID NO HIDDEN INPUT
                    idStoreHidden.value = idStore;
                    movimentDatas(storeDataListInput, idStore)
                }
            }
        }
    }

    storeDataListInput.addEventListener('change', function () {
        // PEGA DO DATA-LIST O ID STORE DO OPTION
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

        }
    }
}

// BEGIN CALC EVENT LISTENNERS
const inputMoviment = document.querySelector("form.app_form#moviment");
if (inputMoviment) {
    document.querySelector("input[name='expend']").addEventListener('keyup', function () {
        if (inputMoviment && document.querySelector('.last_value').textContent) {
            calc('paying_now', this);
        }
    });

    document.querySelector("input[name='paying_now']").addEventListener('keyup', function () {
        if (inputMoviment && document.querySelector('.last_value').textContent) {
            calc('paying_now', document.querySelector("input[name='expend']"));
        }
    });

    document.querySelector("input[name='id_store']").addEventListener('change', function () {
        if (inputMoviment && document.querySelector('.last_value').textContent) {
            calc('paying_now', document.querySelector("input[name='expend']"));
        }
    });

    document.querySelector("input[name='id_store_fake']").addEventListener('input', function () {
        if (inputMoviment && document.querySelector('.last_value').textContent) {
            calc('paying_now', document.querySelector("input[name='expend']"));
        }
    });
}

// END CALC EVENT LISTENNERS

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
        }
        const formMoviment = formMovimentGlobalVar;
        formSub(formMoviment);
    });
}
// END MOVIMENT CALCS


// BEGIN COMO DEFAULT ELE SETA OS INPUTS DE DATA DOS CADASTROS COM A DATA ATUAL
if (window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-lista' ||
    window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-fluxo-de-caixa' ||
    window.location.toString() === 'http://www.localhost/arrecadacao/app/cadastrar-lista' ||
    window.location.toString() === 'http://www.localhost/arrecadacao/app/cadastrar-fluxo-de-caixa' ||
    window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-movimentacao' ||
    window.location.toString() === 'http://www.localhost/arrecadacao/app/cadastrar-movimentacao' ||
    window.location.toString() === 'http://www.localhost/arrecadacao/configuracoes/horario' ||
    window.location.toString() === 'http://www.ihsistemas.com/configuracoes/horario'
) {
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

if (document.querySelector('.app_form#moviment')) {
    const parentModal = document.querySelector('.app_modal.modal_calc_parent');

    // ABRE E FECHA MODAL DE CALCULADORA
    document.addEventListener('keyup', function (e) {
        const modal = document.querySelector('.app_modal_calc');

        if (e.key === 'Control') {
            parentModal.style.display = 'flex';
            modal.style.display = 'block';
            parentModal.dataset.modalclose = 'false';
            // FAZ FOCAR NO INPUT
            modal.children[1].children[0].children[1].focus();

            const payingNow = document.querySelector("input[name=paying_now]");
            const currentResult = document.querySelector('.current_result');
            currentResult.textContent = payingNow.value.toLocaleString('pt-br', {
                maximumFractionDigits: 2,
                minimumFractionDigits: 2
            });
        }

        if (e.key === 'Escape') {
            modal.style.display = 'none';
            parentModal.style.display = 'none';
            parentModal.dataset.modalclose = 'true';

            // ADICIONA O VALOR CALCULADO NO INPUT DE VALOR DINHEIRO CASO EXISTA
            let resultCurrentValue = document.querySelector('.current_result').textContent;
            if (resultCurrentValue) {
                const inputPayingNow = document.querySelector("input[name='paying_now']");
                inputPayingNow.value = resultCurrentValue;
                //re-faz os calculos
                calc('paying_now', document.querySelector("input[name='expend']"));
                inputPayingNow.focus();
            }
        }
    })

    const modalCalc = document.querySelector('.app_modal_calc');
    if (modalCalc) {
        const payingNow = document.querySelector("input[name='paying_now']").value;
        const formCalc = document.querySelector('.app_form.ajax_off');
        const inputCalc = document.querySelector('.input_calc');
        const currentResult = document.querySelector('.current_result');

        // SE JÁ HOUVER VALOR NO INPUT ADICIONA
        if (payingNow) {
            currentResult.textContent = payingNow;
        }
        // REALIZA OS CALCULOS
        formCalc.addEventListener('submit', (e) => {

            if (modalCalc.style.display === 'block') {
                e.preventDefault();
                let currentValue = currentResult.textContent;
                if (currentValue) {
                    currentValue = parseFloat(currentValue.replaceAll('.', '').replace(',', '.'));
                    currentValue += parseFloat(inputCalc.value.replaceAll('.', '').replace(',', '.'));
                    currentResult.textContent = currentValue.toLocaleString('pt-br', {
                        maximumFractionDigits: 2,
                        minimumFractionDigits: 2
                    });
                } else {
                    currentResult.textContent = inputCalc.value.toLocaleString('pt-br', {
                        maximumFractionDigits: 2,
                        minimumFractionDigits: 2
                    });
                }

                inputCalc.value = null;
                inputCalc.focus();
            }
        })
    }


    // ABRE O MODAL DE DADOS DO MOVIMENTO
    const modalMoviment = document.querySelector('.app_modal_moviment');
    const parentModalMoviment = document.querySelector('.app_modal.app_form.modal_moviment_parent');
    if (modalMoviment) {
        document.addEventListener('keyup', function (e) {
            if (e.key === 'i') {
                parentModalMoviment.style.display = 'flex';
                modalMoviment.style.display = 'block';
                parentModalMoviment.dataset.modalclose = 'false';
            }

            if (e.key === 'Escape') {
                modalMoviment.style.display = 'none';
                parentModalMoviment.style.display = 'none';
                parentModalMoviment.dataset.modalclose = 'true';
            }
        })
    }

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

        // após tudo envio o formulário
        formSub(formCashFlow);
    })
}
