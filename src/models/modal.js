import store from '@/store/';

const animSpeed = 250; //in millis

function showModal(modal) {
    if(store.state.modal) {
        store.state.modal = undefined;
        setTimeout(() => {
            store.state.modal = modal;
        }, animSpeed)
    } else {
        store.state.modal = modal;
    }
}

class Modal {
    login(redirect){
        var modal = {
            id: 'id'+(new Date()).getTime(),
            action: 'login',
            redirect
        }

        showModal(modal)
    }
    upload(){
        var modal = {
            id: 'id'+(new Date()).getTime(),
            action: 'upload',
        }

        showModal(modal)
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
export default modal