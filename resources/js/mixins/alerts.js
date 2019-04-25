const Alerts = {
    data() {
        return {
            alerts  : {
                messages    : [],
                errors      : [],
            }
        }
    },
    methods     : {
        add(message, type) {
            this.alerts[type].push(message)
        },
        remove(message, type) {
            this.alerts[type].splice(this.alerts[type].indexOf(message), 1)
        }
    }
}

export default Alerts