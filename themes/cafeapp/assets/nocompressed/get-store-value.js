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

    const formMoviment = document.querySelector('.app_form#moviment');
    if (content) {
        const storeValue = content.store_value;
        lastValue.textContent = toBrNumber(storeValue ?? 0);
        inpuLastValue.value = toBrNumber(storeValue ?? 0);

        if (formMoviment) {
            getMoviment(new FormData(formMoviment));
        }
    }
}