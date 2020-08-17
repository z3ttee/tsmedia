import store from '@/store/';

const animSpeed = 250; //in millis

class Modal {
    login(redirect){
        var modal = {
            id: 'id'+(new Date()).getTime(),
            action: 'login',
            redirect
        }

        if(store.state.modal) {
            store.state.modal = undefined;
            setTimeout(() => {
                store.state.modal = modal;
            }, animSpeed)
        } else {
            store.state.modal = modal;
        }
    }

    dismiss() {
        store.state.modal = undefined;
    }

    modalClicked(event) {
        if(event.target.id === 'modal-container') {
            this.dismiss();
        }
    }

}

const modal = new Modal();

const modalMixin = {
    methods: {
        showLogin() {
            modal.login();
        },
        dismissModal() {
            modal.dismiss();
        },
        modalClicked(event) {
            modal.modalClicked(event);
        }
    }
}

export {modal, modalMixin}