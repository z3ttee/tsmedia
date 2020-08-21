import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuelidate from 'vuelidate'

import Toast from '@/models/toast.js'
import User from '@/models/user.js'
import Api from '@/models/api.js'
import { modalMixin } from '@/models/modal.js'

import AppButton from '@/components/buttons/AppButton.vue'
import AppLoader from '@/components/loader/LoaderView.vue'
import SelectView from '@/components/form/SelectView.vue'

store.commit('initialiseStore')

const app = createApp(App);

app.config.globalProperties.$toast = Toast
app.config.globalProperties.$user = User
app.config.globalProperties.$store = store
app.config.globalProperties.$api = Api

app.config.warnHandler = () => {}

app.use(store)
app.use(router)
app.use(vuelidate)

app.mixin(modalMixin)
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
app.component('app-loader', AppLoader)
app.component('app-select', SelectView)

router.isReady().then(() => app.mount('#wrapper'))
