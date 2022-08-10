export default class extends window.Controller {

    static values = { url: String, refreshInterval: Number }

    connect() {
        this.load()
        if (this.hasRefreshIntervalValue) {
            this.startRefreshing()
        }
    }

    disconnect() {
        this.stopRefreshing()
    }

    load() {
        fetch(this.urlValue)
            .then(response => {
                return response.json()
            })
            .then(data => {
                if(data.complete) {
                    this.stopRefreshing()
                }
                this.element.innerHTML = data.html
            })
    }

    startRefreshing() {
        this.refreshTimer = setInterval(() => {
            this.load()
        }, this.refreshIntervalValue)
    }

    stopRefreshing() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer)
        }
    }
}
