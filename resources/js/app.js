import './bootstrap';

import ImporterController from "./controllers/importer"
application.register("importer", ImporterController)

document.addEventListener("turbo:submit-start", (event) => {
    //event.preventDefault();
    if(window.location.href.indexOf('importar-dados') > -1) {
        const loadingElem = document.getElementById('#processingImportLoading');
        loadingElem.classList.add('d-flex');
        loadingElem.style.display = "block";
    }
})