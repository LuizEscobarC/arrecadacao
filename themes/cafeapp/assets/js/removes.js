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