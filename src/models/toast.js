import store from '@/store/';
import AppToast from '@/components/message/Toast.vue';

const animSpeed = 450; //in millis

class Toast {
    show(data){
        console.log('showing...')

        var toast = {
            id: 'id'+(new Date()).getTime(),
            component: AppToast,
            ...data
        }

        console.log('showing...')

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


export default new Toast();