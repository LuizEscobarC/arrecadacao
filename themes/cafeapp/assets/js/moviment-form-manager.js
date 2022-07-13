const formMoviment = document.querySelector('.app_form#moviment');
const flashClass = "ajax_response";
const flash = document.querySelector("." + flashClass);
if (formMoviment) {
    formMoviment.addEventListener('submit', (e) => {
        e.preventDefault();
        const idSaveTemp = document.querySelector('#moviment_btn').dataset.savetemp;
        if (idSaveTemp) {
            const wantSave = confirm('Deseja mesmo salvar esse movimento?');
            // SALVA O MOVIMENTO TEMPORARIO
            if (wantSave) {
                ajax(formMoviment.getAttribute('action'), new URLSearchParams({
                        doTheJobs: 1,
                        id_temporary_moviment: idSaveTemp
                    }), 'POST',
                    'application/x-www-form-urlencoded').then(response => {
                    if (response.message) {
                        if (!flash.textContent) {
                            // AO INVES DE USAR UM PLUGIN JQUERY, USO SOMENTE CLASSES CSS
                            flash.innerHTML = response.message;
                            flash.style.display = 'flex';
                            flash.classList.add('bounce', 'animated')
                        } else {
                            // INSERE NO COMEÇO DO FORMULÁRIO
                            formMoviment.insertAdjacentHTML("afterbegin",
                                "<div class='" + flashClass + " bounce animated'>" + response.message + "</div>");
                        }
                    }

                    if (response.reload) {
                        window.location.reload()
                    }
                });
            }

            if (!wantSave) {
                // DELETA O MOVIMENTO TEMPORÁRIO
                ajax(formMoviment.getAttribute('action'), new URLSearchParams({
                    id_temporary_moviment: idSaveTemp,
                    delete: 1
                }), 'POST', 'application/x-www-form-urlencoded').then( response => {
                    if (response) {
                        window.location.reload();
                    }
                });
            }
        } else {
            ajaxFormMoviment(formMoviment);
        }
    })
}


// ajax
const ajaxFormMoviment = async (formDataParam) => {
    formDataParam = new FormData(formDataParam);
    formDataParam.set('shouldBeatPrizeStore', 0);

    const toCalcLastValue = (formDataParam.get('last_value') === '' ? 0 : toAppNumber(formDataParam.get('last_value')));
    const toCalcPayingNow = (formDataParam.get('paying_now') === '' ? 0 : toAppNumber(formDataParam.get('paying_now')));
    const toCalcExpend = (formDataParam.get('expend') === '' ? 0 : toAppNumber(formDataParam.get('expend')));
    const toCalcNetValue = (formDataParam.get('net_value') === '' ? 0 : toAppNumber(formDataParam.get('net_value')));
    // vai no back e pega o valor se abate ante de realizar os calculos
    const beatValue = toCalcLastValue + (toCalcPayingNow + toCalcExpend - toCalcNetValue);
    if (beatValue) {
        if (beatValue < 0) {
            if (toAppNumber(formDataParam.get('prize')) > 0) {
                const shouldBeatPrizeStore = confirm('Deseja abater no saldo da loja?');
                if (shouldBeatPrizeStore) {
                    formDataParam.set('shouldBeatPrizeStore', 1);
                }
            }
        }
    }

    const url = formMoviment.getAttribute('action');
    const method = 'POST';
    const body = new URLSearchParams(formDataParam);
    const contentType = 'application/x-www-form-urlencoded';

    const response = await ajax(url, body, method, contentType);
    if (response) {

        if (response.data.idMovimentTemporary) {
            const data = response.data;
            const selector = (selector) => document.querySelector('.' + selector);
            // APRESENTANDO OS DADOS NOS CAMPOS DE APRESENTAÇÃO
            selector('new_value').textContent = toBrNumber(data.new_value);
            selector('get_value').textContent = toBrNumber(data.get_value);
            selector('cents').textContent = toBrNumber((data.cents ?? 0));
            selector('cents').value = toBrNumber(data.cents);
            selector('beat_value').textContent = toBrNumber(data.beat_value);
            selector('prize').value = toBrNumber(data.prize);
            selector('prize_office').textContent = toBrNumber(data.prize_office);
            selector('prize_store').textContent = toBrNumber(data.prize_store);
            selector('id_temporary_moviment').value = data.idMovimentTemporary;
            document.querySelector("#moviment_btn").textContent = 'Salvar o Movimento';
            document.querySelector("#moviment_btn").dataset.savetemp = data.idMovimentTemporary;
            return true;
        }

        if (response.message) {
            if (!flash.textContent) {
                // AO INVES DE USAR UM PLUGIN JQUERY, USO SOMENTE CLASSES CSS
                flash.innerHTML = response.message;
                flash.style.display = 'flex';
                flash.classList.add('bounce', 'animated')
            } else {
                // INSERE NO COMEÇO DO FORMULÁRIO
                formMoviment.insertAdjacentHTML("afterbegin",
                    "<div class='" + flashClass + " bounce animated'>" + response.message + "</div>");
            }

            if (response.message.reload) {
                window.location.reload();
            }
        }
    }
}
