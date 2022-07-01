const formMovimentGlobalVar = document.querySelector('form.app_form#moviment');

const ajax = async (url, data, method, contentType) => {
    const load = document.querySelector(".ajax_load");
    load.style.display = 'flex';
    const callback = await fetch(url, {
        method: method,
        body: data,
        headers: {
            'Content-Type': contentType
        }
    });
    load.style.display = 'none';
    return await callback.json();
}

function blockerButton(button) {
    button.setAttribute('disabled', true);
}

function addButtonMoviment(button) {
    button.removeAttribute('disabled');
}

function toAppNumber(value) {
    console.log(value)
    return parseFloat(value.replaceAll('.', '').replace(',', '.'));
}

function toBrNumber(value) {
    return parseFloat(value).toLocaleString('pt-br', {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2
    });
}

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
           /* let link = response.link;
            document.querySelector('.app_form#moviment').insertAdjacentHTML('afterbegin', `
            <label class="link_current_moviment">
                <p class="app_widget_title padding_btn gradient gradient-blue gradient-hover radius transition">
                    <a class="desc moviment color_white" style="text-decoration: none;"
                       href="${link}">CLIQUE AQUI para editar esse lançamento.</a></p>
            </label>
       `); */
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
            document.querySelector('input[name="paying_now"]').value = (response.moviment.paying_now ?? 0);
            selector('expend_view').textContent = response.moviment.expend;
            document.querySelector('input[name="expend"]').value = (response.moviment.expend ?? 0);
            selector('get_value_view').textContent = response.moviment.get_value;
            selector('beat_value_view').textContent = response.moviment.beat_value;
            selector('new_value_view').textContent = response.moviment.new_value;
            selector('prize_value_view').textContent = response.moviment.prize;
            document.querySelector('input[name="prize"]').value = (response.moviment.prize ?? 0);
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

    // SE HOUVER RETORNO DE LISTA BY DATA, LOJA E HORARIO
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

    // TEST DE MOVIMENTO
   // netValue = 28;

    document.querySelector('.total_value').textContent = totalValue;
    document.querySelector("input[name='total_value']").value = totalValue;
    document.querySelector('.comission_value').textContent = comissionValue;
    document.querySelector("input[name='comission_value']").value = comissionValue;
    document.querySelector('.net_value').textContent = netValue;
    document.querySelector("input[name='net_value']").value = netValue;
}

async function getStoreValueNow(inputDataList, idStore, idHour, dateMoviment) {
    const data = {};
    lastValue = document.querySelector('.last_value');
    inpuLastValue = document.querySelector("input[name='last_value']");

    data['id_store'] = idStore;
    data['id_hour'] = idHour;
    data['date_moviment'] = dateMoviment;
    const callback = await fetch(inputDataList.dataset.url, {
        method: 'POST', body: new URLSearchParams(data)
    })

    const content = await callback.json();

    if (content) {
        const storeValue = content.store_value;
        lastValue.textContent = toBrNumber(storeValue ?? 0);
        inpuLastValue.value = toBrNumber(storeValue ?? 0);

        // TEST DE MOVIMENTO
        // lastValue.textContent = -100;
        // inpuLastValue.value = -100;

        getMoviment(new FormData(document.querySelector('.app_form#moviment')));
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
        let storeOption = document.getElementById(dataValue).options.namedItem(this.value);
        const idStoreHidden = document.querySelector("input[name='id_store']");
        const idHour = document.querySelector("select[name='id_hour']").value;
        const dateMoviment = document.querySelector("input[name='date_moviment']").value;

        if (!(idStoreHidden && idHour && dateMoviment)) {
            alert('Data de movimento e horário são necessários.');
            window.location.reload();
        }

        // SE EXISTIR
        if (storeOption) {
            const idStore = storeOption.dataset.id_store;
            // ADICIONA O ID NO HIDDEN INPUT
            idStoreHidden.value = idStore;
            //FAZ AS ROTINAS DE VERIFICAÇÃO E FETCH NOS DADOS NECESSÁRIOS SOMENTE PARA O MOVIMENT
            if (document.querySelector('form.app_form#moviment, .cash_flow')) {
                getStoreValueNow(this, idStore, idHour, dateMoviment);
                if (document.querySelector('form.app_form#moviment')) {
                    //storeVerify(idStore);
                    movimentDatas(this, idStore)
                }
            }
        }
    });
}

// END CALC EVENT LISTENNERS

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
