import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import LottiePlayer from 'lottie-player-vue';

import MessageBox from '@/components/message/MessageBox.vue';

Vue.use(LottiePlayer);

Vue.component('app-message-box', MessageBox)

Vue.config.productionTip = false

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
