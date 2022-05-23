//SELECIONO O FORMULÁRIO ATUAL EXCETO O FORMULARIO DE MOVIMENTO QUE TEM UMA REGRA DE NEGÓCIO DIFERENTE RELACIONADO A SUBMISSÃO DE FORMULÁRIO
const formSubmit = (document.querySelector(".app_form:not(.app_form#moviment , .ajax_off)")
    ? document.querySelector(".app_form:not(.app_form#moviment, .ajax_off)")
    : document.querySelector('.auth_form'));

// ESPERO O EVENTO DO ENVIO DO FORMULÁRIO
if (formSubmit) {
    formSubmit.addEventListener('submit', function (e) {
        e.preventDefault();
        formSub(this)
    })
}

// FUNCTION
async function formSub(form) {
    const load = document.querySelector(".ajax_load");
    const flashClass = "ajax_response";
    const flash = document.querySelector("." + flashClass);

    load.style.display = "flex";

    // ESPERA O ENVIO COM O RETORNO
    const callback = await fetch(
        form.getAttribute("action"), {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams(new FormData(form))
        })

    // SE RETORNAR A PROMESSA FOR CUMPRIDA PEGA OS DADOS
    const response = await callback.json();

    //MANIPULA OS DADOS
    if (response) {
        if (response.scroll) {
            window.scrollTo({top: response.scroll, behavior: 'smooth'});
        }

        // REDIRECIONA
        if (response.redirect) {
            setTimeout(function () {
                window.location.href = response.redirect;
            }, (response.timeout ?? 10));
        }

        // ATUALIZA A PAGINA
        if (response.reload) {
            window.location.reload();
        }

        //MESSAGE PREPEND / DISPARO DE MENSAGEM
        if (response.message) {
            if (!flash.textContent) {
                // AO INVES DE USAR UM PLUGIN JQUERY, USO SOMENTE CLASSES CSS
                flash.innerHTML = response.message;
                flash.style.display = 'flex';
                flash.classList.add('bounce', 'animated')
            } else {
                // INSERE NO COMEÇO DO FORMULÁRIO
                form.insertAdjacentHTML("afterbegin",
                    "<div class='" + flashClass + " bounce animated'>" + response.message + "</div>");
            }
        } else {
            flash.style.display = "none";
        }
        // AO FINAL DE TUDO O LOADER SAÍ
        load.style.display = "none";
    }
}