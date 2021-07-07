import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import AllianceWydgetPlugin from "alliance-wydget-vue"
import VueSecurPlugin from "secur-vue"

const app = createApp(App)

app.use(store)
app.use(router)
app.use(AllianceWydgetPlugin);
app.use(VueSecurPlugin, {
    protocol: "http",
    host: "localhost",
    port: 3333,
    path: "/",
    guardConfig: {
        homeRoute: { name: "home" },
        loginRoute: "https://tsalliance.eu/auth/login",
        errorHandler: undefined
    }
})

app.mount('#app')
