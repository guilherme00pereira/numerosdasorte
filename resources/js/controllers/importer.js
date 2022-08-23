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
        this.fetchData().then(data => {
            if(data.complete) {
                this.stopRefreshing()
            }
            this.element.innerHTML = data.html
        })
    }

    async fetchData() {
        const response = await fetch(this.urlValue)
        const json = await response.json()
        return json
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
