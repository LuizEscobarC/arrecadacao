const $body = $("body");
const formMovimentGlobalVar = document.querySelector('form.app_form#moviment');

// BEGIN FUNCTIONS
/## GET HOUR DRY FUNCTION ##/

async function getHours(inputSelect) {
    const select = document.querySelector('select.callback');
    const label = document.querySelector('p#label');
    const data = {};
    data[inputSelect.getAttribute('name')] = inputSelect.value;

    const callback = await fetch(inputSelect.getAttribute('rel'), {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams(data)
    })
    const response = await callback.json();
    if (response) {
        label.innerHTML = response[0];
        response.shift();
        select.innerHTML = '<option value="">Escolha</option>';
        for (hour of response) {
            select.insertAdjacentHTML('beforeend',
                `<option value="${hour.id}"> ${hour.description}</option>`);
        }
    }
}

async function getList(inputDataList, idHour, idStore) {
    const data = {id_hour: idHour, id_store: idStore};
    const url = inputDataList.getAttribute('rel');
    const ajaxResponse = document.querySelector('.ajax_response');

    const callback = await fetch(url,
        {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams(data)
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
    const callback = await fetch(inputDataList.dataset.url,
        {
            method: 'POST',
            body: new URLSearchParams(data)
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
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        method: 'POST',
        body: data
    })

    const response = await callback.json();

    if (response.message) {
        flashClass.insertAdjacentHTML("afterend",
            response.message);
    }
}

/## REMOVE ENTITY DRY FUNCTION ##/

async function remove(dataAttr, confirmText) {
    const selected = document.querySelector(`[data-${dataAttr}]`);
    if (selected) {
        selected.addEventListener('click', async function () {
            const remove = confirm('ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir ' + confirmText);
            const url = this.dataset[dataAttr];

            if (remove === true) {
                const callback = await fetch(url,
                    {
                        method: 'POST',
                        data: {},
                        headers: {'Content-Type': 'application/json'}
                    });

                const response = await callback.json();

                if (response) {
                    if (response.scroll) {
                        window.scrollTo({ top: response.scroll, behavior: 'smooth' });
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


/*
 * MOBILE MENU
 */
const appSidebar = document.querySelector(".app_sidebar");

document.querySelector("li[data-mobilemenu]").addEventListener('click', function () {
    const action = this.dataset.mobilemenu;

    if (action === 'open') {
        appSidebar.style.display = 'block';
    }
});

document.querySelector("div[data-mobilemenu]").addEventListener('click', function () {
    const action = this.dataset.mobilemenu;

    if (action === 'close') {
        appSidebar.style.display = 'none';
    }
});

document.getElementById('sidebar').addEventListener('click', function () {
    const clickedClassList = this.classList;
    const $appDrop = document.querySelector(".app_drop");

    if (clickedClassList.contains('open')) {
        $appDrop.classList.add('slidedown');
        clickedClassList.remove('open');
        $appDrop.style.display = 'block';
    } else {
        $appDrop.classList.add('slideup');
        clickedClassList.add('open');
        $appDrop.style.display = 'none';
    }
});

document.getElementById('sidebar2').addEventListener('click', function () {
    const clickedClassList = this.classList;
    const $appDrop = document.querySelector(".app_drop1");

    if (clickedClassList.contains('open')) {
        $appDrop.classList.add('slidedown');
        clickedClassList.remove('open');
        $appDrop.style.display = 'block';
    } else {
        $appDrop.classList.add('slideup');
        clickedClassList.add('open');
        $appDrop.style.display = 'none';
    }
});

document.getElementById('sidebar3').addEventListener('click', function () {
    const clickedClassList = this.classList;
    const $appDrop = document.querySelector(".app_drop2");

    if (clickedClassList.contains('open')) {
        $appDrop.classList.add('slidedown');
        clickedClassList.remove('open');
        $appDrop.style.display = 'block';
    } else {
        $appDrop.classList.add('slideup');
        clickedClassList.add('open');
        $appDrop.style.display = 'none';
    }
});

// BEGIN REMOVE ENTITIES
const dataRemoveEntities = {
    "hourremove": "esse horário?",
    "userremove": "esse usuário?",
    "centerremove": "esse centro de custo?",
    "storeremove": "essa loja?",
    "listremove": "essa lista?",
    "cashremove": "esse lançamento?",
    "moviment-remove": "essa movimentação?"
}

for (keysRemove in dataRemoveEntities) {
    remove(keysRemove, dataRemoveEntities[keysRemove])
}
// END REMOVE ENTITIES

/*
* AJAX GET HOUR
*/
const hourInput = document.querySelector('input.hour');

hourInput.addEventListener('change', function () {
    const select = document.querySelector('select.callback');
    const label = document.getElementById('label');
    if (select || label) {
        select.textContent = '';
        label.textContent = '';
    }
    getHours(this);
});

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
        getList(inputDataList, hourSelect.value, idStore);
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
            if (document.querySelector('form.app_form#moviment')) {
                storeVerify(idStore);
                getStoreValueNow(this, idStore);
                movimentDatas(this, idStore)
            }
        }

    });
}

document.querySelector('select.callback').addEventListener('change', function () {
    movimentDatas(null)
});

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
        const last_val = document.querySelector('p.last_value');


        document.querySelector("input[name='last_value']").value = last_val.textContent.toLocaleString('pt-br', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        document.querySelector("input[name='net_value']").value = netValue;
        document.querySelector("input[name='get_value']").value = getValueBr;
        document.querySelector('.get_value').innerHTML = getValueBr;

        if (last_val.textContent && netValue) {
            // É o valor que tem que ser abatido  com o valor recolhido + o valor de despesas
            // VALOR A ACERTAR | VALOR LIQUIDO
            const beatValue = (getValue - parseFloat(netValue.replaceAll('.', '')
                .replace(',', '.')));

            // NOVO VALOR ATUAL | SALDO ANTERIOR
            const newValue = (parseFloat(last_val.textContent.replaceAll('.', '')
                .replace(',', '.')) + beatValue)
                .toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            const beatValueBrl = beatValue.toLocaleString('pt-br', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
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
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    const beatPrizeBrl = beatPrize.toLocaleString('pt-br', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    const prizeOfficeBrl = prizeOffice.toLocaleString('pt-br', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    const prizeStoreBrl = prizeStore.toLocaleString('pt-br', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    document.getElementsByClassName('new_value').textContent = newStoreValueBrl;
                    document.querySelector("input[name='new_value']").textContent = newStoreValueBrl;

                    document.querySelector('label.prize_output').insertAdjacentHTML('afterbegin',
                        `<span class="field icon-leanpub">Valor de Abate Premio:</span>
                            <p class="app_widget_title beat_prize">${beatPrize}</p>
                            <input type="hidden" name="beat_prize" value="${beatPrizeBrl}">
                            <input type="hidden" name="prize_office" value="${prizeOfficeBrl}">
                            <input type="hidden" name="prize_store" value="${prizeStoreBrl}">`);
                    alert(`A loja pagará: R$${prizeStoreBrl}.
                     O escritório pagará: R$${prizeOfficeBrl}.`);
                }
            } else {
                inputPrize = inputPrize.toLocaleString('pt-br', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                document.querySelector('label.prize_output').insertAdjacentHTML('afterbegin',
                    `<span class="field icon-leanpub">Sem abate</span>
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

// BEGIN COMO DEFAULT ELE SETA OS INPUTS DE DATA DOS CADASTROS COM A DATA ATUAL
if (window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-lista' || window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-fluxo-de-caixa' || window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-movimentacao') {
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
                pattern: /[-]/,
                optional: true
            }
        }, reverse: true, placeholder: '0,00'
    });
    $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
    $(".mask-date").mask('00/00/0000', {reverse: true});
    $(".mask-month").mask('00/0000', {reverse: true});
    $(".mask-doc").mask('000.000.000-00', {reverse: true});
    $(".mask-day").mask('00', {reverse: true});
});