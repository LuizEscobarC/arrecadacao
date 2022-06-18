const formMovimentGlobalVar = document.querySelector('form.app_form#moviment');


function blockerButton(button) {
    button.setAttribute('disabled', true);
}

function addButtonMoviment(button) {
    button.removeAttribute('disabled');
}

function toAppNumber(value) {
    return parseFloat(value.replaceAll('.', '').replace(',', '.'));
}

function toBrNumber(value) {
    return value.toLocaleString('pt-br', {
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
            let link = response.link;
            document.querySelector('.app_form#moviment').insertAdjacentHTML('afterbegin', `
            <label class="link_current_moviment">
                <p class="app_widget_title padding_btn gradient gradient-blue gradient-hover radius transition">
                    <a class="desc moviment color_white" style="text-decoration: none;"
                       href="${link}">CLIQUE AQUI para editar esse lançamento.</a></p>
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
    inpuLastValue = document.querySelector("input[name='last_value']");

    data['id_store'] = idStore;
    const callback = await fetch(inputDataList.dataset.url, {
        method: 'POST', body: new URLSearchParams(data)
    })

    const content = await callback.json();

    if (content) {
        lastValue.textContent = parseFloat(content.valor_saldo).toLocaleString('pt-br', {minimumFractionDigits: 2});
        inpuLastValue.value = toBrNumber(parseFloat(content.valor_saldo));
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

/**
 *                         const hasCents = prizeOfficeToString.search(/\.[0-9]{1,}$/);
 *                         if (hasCents !== -1) {
 *                             let cents = prizeOfficeToString.slice(-2);
 *                             let newOfficePrizePay = prizeOfficeToString.slice(0, hasCents);
 *                             prizeOffice = parseFloat(newOfficePrizePay);
 *                             newStoreValue = parseFloat('0.' + cents);
 *                         }
 */

// END CALC EVENT LISTENNERS

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
