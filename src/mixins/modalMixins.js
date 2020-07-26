import store from '@/store/index.js'

const mixin = {
    methods: {
        showModal(type, data, buttons) {
            switch (type) {
                case 'login':
                    this.showLogin(data, buttons)
                    break
                default:
                    this.showInfo(data, buttons)
                    break
            }
        },
        showLogin(d, buttons) {
            
            var data = d || {}
            console.log(data);
            var modal = {
                id: 'id'+(new Date()).getTime(),
                data,
                buttons,
                component: () => import('@/components/modal/LoginModal.vue')
            }
            store.state.activeModals.push(modal);
        },
        showInfo(d, buttons) {
            var data = d || {}
            var modal = {
                id: 'id'+(new Date()).getTime(),
                data,
                buttons,
                component: () => import('@/components/modal/InfoModal.vue')
            }
            store.state.activeModals.push(modal);
        },
        dismissModal(modalID) {
            var modal = store.state.activeModals.filter((m) => { if(m.id == modalID) return m; })[0];
            var index = store.state.activeModals.indexOf(modal);
            if(modal.data.ondismiss) modal.data.ondismiss();
            store.state.activeModals.splice(index, 1);
        },
        dismissAll() {
            for(var modal of store.state.activeModals){
                if(modal.data.ondismiss) modal.data.ondismiss();
            }
            store.state.activeModals = []
        },
        dismissIndex(index) {
            var modal = store.state.activeModals[index];
            if(modal.data.ondismiss) modal.data.ondismiss();
            store.state.activeModals.splice(index, 1);
        },
        containerClicked(event) {
            if(event.target.id === 'modal-container') {
                this.dismissIndex(0);
            }
        }
    }
}

export default mixin;