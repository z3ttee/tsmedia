import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/index.js'
import axios from 'axios'
import UUID from 'vue-uuid'
import LottiePlayer from 'lottie-player-vue';

import MessageBox from '@/components/message/MessageBox.vue';
import PrimaryLoadingButton from '@/components/button/PrimaryLoadingButton.vue';

import modalMixin from '@/mixins/modalMixins.js';

Vue.use(LottiePlayer);
Vue.use(UUID);

Vue.component('app-message-box', MessageBox);
Vue.component('primary-loading-btn', PrimaryLoadingButton);

Vue.mixin(modalMixin);

axios.defaults.baseURL = 'http://localhost/api/v1/';
axios.defaults.headers.common['Authorization'] = 'Bearer '+store.state.user.access_token;

Vue.prototype.$http = axios;

Vue.config.productionTip = false

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')