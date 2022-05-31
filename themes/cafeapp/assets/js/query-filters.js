const formPaidPrize = document.querySelector('#filter_paid_prizes');
const formStoreBalance = document.querySelector('#filter_store_balance');

// BEGIN PAIDPRIZE
const ajaxPaidPrize = async (element) => {
    // BEGIN GETTING DATALIST IDS
    const store = document.querySelector('.search_store').value;
    const cost = document.querySelector('.search_cost').value;
    const hour = (document.querySelector('.search_hour').value ?? '');
    const dateId = (document.querySelector('.search_date').value ?? '');

    const storeId = (store ? document.querySelector(`#search_stores option[value='${store}']`).dataset.id : '');
    const costId = (cost ? document.querySelector(`#search_costs option[value='${cost}']`).dataset.id : '');
    // END GETTING DATALIST ID


    // OBJECT DATA
    const data = {store: storeId, cost: costId, hour: hour, date: dateId, route: 'premios-pagos'};

    const url = element.getAttribute('action');
    const method = 'POST';
    const body = new URLSearchParams(data);
    const contentType = 'application/x-www-form-urlencoded';

    const response = await ajax(url, body, method, contentType);

    if (response) {
        if (response.redirect) {
            window.location.href = response.redirect;
        }
    }

}
// END PAIDPRIZE

// BEGIN STORE BALANCE
const ajaxStoreBalance = async (element) => {
    // BEGIN GETTING DATALIST IDS
    const store = document.querySelector('.search_store').value;
    const hour = (document.querySelector('.search_hour').value ?? '');
    const dateMoviment = (document.querySelector('.search_date').value ?? '');

    const storeId = (store ? document.querySelector(`#search_stores option[value='${store}']`).dataset.id : '');
    const hourId = (hour ? document.querySelector(`#search_hours option[value='${hour}']`).dataset.id : '');

    // END GETTING DATALIST ID

    // OBJECT DATA
    const data = {store: storeId, hour: hourId, date_moviment: dateMoviment, route: 'consultar-saldo-da-loja'};

    const url = element.getAttribute('action');
    const method = 'POST';
    const body = new URLSearchParams(data);
    const contentType = 'application/x-www-form-urlencoded';

    const response = await ajax(url, body, method, contentType);

    if (response) {
        if (response.redirect) {
            window.location.href = response.redirect;
        }
    }

}
// END STORE BALANCE

if (formPaidPrize) {
    formPaidPrize.addEventListener('submit', (e) => {
        e.preventDefault();
        ajaxPaidPrize(e.target);
    })
}

if (formStoreBalance) {
    formStoreBalance.addEventListener('submit', (e) => {
        e.preventDefault();
        ajaxStoreBalance(e.target);
    })
}