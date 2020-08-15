import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import AppToast from '@/models/toast.js'
import AppModal from '@/models/modal.js'

import AppButton from '@/components/buttons/AppButton.vue'

const app = createApp(App);

app.use(store)
app.use(router)

app.mixin(AppToast)
app.mixin(AppModal)

app.component('app-button', AppButton)

app.config.isCustomElement = ['lottie-player']

app.mount('#wrapper')
