import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import AllianceWydgetPlugin from "alliance-wydget-vue"

const app = createApp(App)


app.use(store)
app.use(router)
app.use(AllianceWydgetPlugin);

app.mount('#app')
