import store from '@/store/index.js'
import InfoNotice from '@/components/modal/InfoNotice.vue'

const mixin = {
    methods: {
        showNotice(d) {
            var notice = {
                id: 'id'+(new Date()).getTime(),
                component: InfoNotice,
                ...d
            }

            this.displayNotice(notice)
        },
        dismissNotice() {
            store.state.notice.current = undefined;
        },
        displayNotice(notice) {
            if(store.state.notice.current) {
                store.state.notice.current = undefined;
                setTimeout(() => {
                    store.state.notice.current = notice;
                }, 300)
            } else {
                store.state.notice.current = notice;
            }
        }
    }
}

export default mixin;