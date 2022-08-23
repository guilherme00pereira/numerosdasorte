import './bootstrap';

import ImporterController from "./controllers/importer"
application.register("importer", ImporterController)

document.addEventListener("turbo:load", (event) => {
    if(window.location.href.indexOf('importar-dados?processing') > -1) {
        const loadingElem = document.getElementById('processingImportLoading');
        const statusElem = document.getElementById('statusImportBox');
        loadingElem.classList.add('d-flex');
        loadingElem.style.display = "block";
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');
        if('customers' === type) {
            fetchCustomers().then(data => {
                if(data.success) {
                    statusElem.classList.remove('alert-info')
                    statusElem.classList.add('alert-success')
                    statusElem.innerText = "Importação concluída com sucesso!"
                } else {
                    statusElem.classList.remove('alert-info')
                    statusElem.classList.add('alert-danger')
                    statusElem.innerText = "Erro ao realizar a importação!"
                }
                loadingElem.classList.remove('d-flex');
                loadingElem.style.display = "none";
            })
        }
        if('orders' === type) {
            fetchOrders().then(data => {
                console.log(data)
                loadingElem.style.display = "none";
            })
        }
    }
})

async function fetchCustomers() {
    const response = await fetch('http://' + window.location.host + '/asyncRunImportCustomers');
    const json = await response.json();
    return json;
}

async function fetchOrders() {
    const response = await fetch('http://' + window.location.host + '/asyncRunImportOrders');
    const json = await response.json();
    return json;
}

