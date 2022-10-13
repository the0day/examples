//exports.return = function (msg) {
window.noticesHandler = function () {
    return {
        notices: [],
        visible: [],
        addBunch(notices) {
            for (let i = 0; i < notices.length; i++) {
                this.add(notices[i]);
            }
        },
        addOne(type, message) {
            let notice = {
                'id': Date.now(),
                'type': type,
                'text': message
            };
            this.notices.push(notice)
            this.fire(notice.id)
        },
        add(notice) {
            notice.id = Date.now()
            this.notices.push(notice)
            this.fire(notice.id)
        },
        fire(id) {
            this.visible.push(this.notices.find(notice => notice.id == id))
            const timeShown = 5000 * this.visible.length
            setTimeout(() => {
                this.remove(id)
            }, timeShown)
        },
        remove(id) {
            const notice = this.visible.find(notice => notice.id == id)
            const index = this.visible.indexOf(notice)
            this.visible.splice(index, 1)
        },
    }
}

var Message = class {

    error(message) {
        if (!message) {
            return;
        }

        document.getElementById('notifications').__x.$data.addOne('error', message);
        console.log("New error: " + message);
    }

    success(message) {
        if (!message) {
            return;
        }

        console.log("New success: " + message);
    }

}
