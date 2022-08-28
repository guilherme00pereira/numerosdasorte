import './bootstrap';

import ImporterController from "./controllers/importer"
application.register("importer", ImporterController)


document.addEventListener("turbo:load", (event) => {
    if(window.location.href.indexOf('importar-dados?processing') > -1) {
        console.log('load')
        setTimeout( function(){
            console.log('fetching')
            const statusElem = document.getElementById('statusImportBox');
            const loadingElem = document.getElementById('processingImportLoading');
            loadingElem.classList.add('d-flex');
            loadingElem.style.display = "block";
            fetch('https://' + window.location.host + '/asyncGetImportStatus')
                .then((resp => resp.json()))
                .then((data) => {
                    if (data.complete) {
                        statusElem.classList.remove('alert-info')
                        statusElem.classList.add('alert-success')
                        statusElem.innerText += "Importação concluída com sucesso!"
                        loadingElem.classList.remove('d-flex');
                        loadingElem.style.display = "none";
                    } else {
                        statusElem.innerText += data.html
                    }
                })
        } ,3000)
    }
})


