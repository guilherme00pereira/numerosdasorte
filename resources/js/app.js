import './bootstrap';

import ImporterController from "./controllers/importer"
application.register("importer", ImporterController)

document.addEventListener("turbo:load", (event) => {
    if(window.location.href.indexOf('importar-dados?processing') > -1) {
        const loadingElem = document.getElementById('processingImportLoading');
        loadingElem.classList.add('d-flex');
        loadingElem.style.display = "block";
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');
        if('customers' === type) {
            fetch('http://' + window.location.host + '/asyncRunImportCustomers').then(response => response.json).then(data => {
                console.log(data.success)
                loadingElem.style.display = "none";
            })
        }
        if('orders' === type) {
            fetch('http://' + window.location.host + '/asyncRunImportOrders').then(response => response.json).then(data => {
                console.log(data)
                loadingElem.style.display = "none";
            })
        }
    }
})

