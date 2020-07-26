import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/index.js'
import axios from 'axios'
import UUID from 'vue-uuid'
import LottiePlayer from 'lottie-player-vue';

import MessageBox from '@/components/message/MessageBox.vue';

import modalMixin from '@/mixins/modalMixins.js';

Vue.use(LottiePlayer);
Vue.use(UUID);

Vue.component('app-message-box', MessageBox);
Vue.mixin(modalMixin);

Vue.prototype.$http = axios;

Vue.config.productionTip = false

axios.defaults.baseURL = 'http://localhost/api/v1/';
axios.defaults.headers.common['Authorization'] = 'Bearer '+123;

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')