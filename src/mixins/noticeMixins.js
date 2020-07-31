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

            if(!store.state.notice.current) {
                this.displayNotice(notice)
            } else {
                store.state.notice.queue.push(notice);
            }
        },
        dismissNotice() {
            store.state.notice.current = undefined;

            if(store.state.notice.queue.length > 0) {
                this.displayNotice(store.state.notice.queue[0]);
                store.state.notice.queue.splice(0, 1);
            }
        },
        displayNotice(notice) {
            store.state.notice.current = notice;
        }
    }
}

export default mixin;