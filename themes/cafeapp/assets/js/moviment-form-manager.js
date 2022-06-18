const formMoviment = document.querySelector('.app_form#moviment');
if (formMoviment) {
    formMoviment.addEventListener('submit', (e) => {
        e.preventDefault();
        ajaxFormMoviment(formMoviment)
    })
}



// ajax
const ajaxFormMoviment = async (formMoviment) => {
    const formData = new FormData(formMoviment);

    const url = formMoviment.getAttribute('action');
    const method = 'POST';
    const body = new URLSearchParams(formData);
    const contentType = 'application/x-www-form-urlencoded';

    const response = await ajax(url, body, method, contentType);

    if (response) {

        // atualiza se salvou
        (response.reload ? window.location.reload() : null);

        if (response.data.saveMoviment) {
            // vai apresentar um alerta pedindo para confirmar se deve salvar ou não

            // se sim submita com o campo saveMoviment
        }

        if (response.message.error) {
            // dispara mensagem na tela
        }
    }

}