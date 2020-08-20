import store from '@/store/';

const animSpeed = 250; //in millis

class Toast {
    show(data){
        var toast = {
            id: 'id'+(new Date()).getTime(),
            ...data
        }

        if(store.state.toast) {
            store.state.toast = undefined;
            setTimeout(() => {
                store.state.toast = toast;
            }, animSpeed)
        } else {
            store.state.toast = toast;
        }
    }

    error(message) {
        this.show({ type: 'error', content: message });
    }
    success(message) {
        this.show({ type: 'success', content: message });
    }

    dismiss() {
        store.state.toast = undefined;
    }

}

export default new Toast()