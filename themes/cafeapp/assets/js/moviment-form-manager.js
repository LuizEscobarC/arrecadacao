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
                        doTheJobs: true,
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

                        if (response.message.reload) {
                            window.location.reload();
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
                    delete: true
                }), 'POST', 'application/x-www-form-urlencoded');
            }
        } else {
            ajaxFormMoviment(formMoviment);
        }
    })
}


// ajax
const ajaxFormMoviment = async (formDataParam) => {
    formDataParam = new FormData(formDataParam);
    formDataParam.set('shouldBeatPrizeStore', false);
    const beatValue = selector('beat_value');

    if (beatValue) {
        if (toAppNumber(beatValue) < 0) {
            if (toAppNumber(formDataParam.get('prize')) > 0) {
                const shouldBeatPrizeStore = confirm('Deseja abater no saldo da loja?');
                if (shouldBeatPrizeStore) {
                    formDataParam.set('shouldBeatPrizeStore', true);
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
            selector('new_value').textContent = data.new_value;
            selector('get_value').textContent = data.get_value;
            selector('cents').textContent = (data.cents ?? 0);
            selector('cents').value = data.cents;
            selector('beat_value').textContent = data.beat_value;
            selector('prize').value = data.prize;
            selector('prize_office').textContent = data.prize_office;
            selector('prize_store').textContent = data.prize_store;
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
