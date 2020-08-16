import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

import { toastMixin } from '@/models/toast.js'
import AppModal from '@/models/modal.js'

import AppButton from '@/components/buttons/AppButton.vue'

axios.defaults.baseURL = 'http://localhost/api/v1/';
axios.defaults.headers.common['Authorization'] = 'Bearer '+store.state.user.access_token;

const app = createApp(App);

app.use(store)
app.use(router)

app.mixin(toastMixin)
app.mixin(AppModal)
app.mixin({
    computed: {
        isLoggedIn() {
            return store.state.user.access_token != undefined
        },
        user() {
            return store.state.user
        }
    }
})

app.component('app-button', AppButton)

app.config.isCustomElement = ['lottie-player']

store.commit('initialiseStore')
app.mount('#wrapper')
