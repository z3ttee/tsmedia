import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuelidate from 'vuelidate'

import Toast from '@/models/toast.js'
import User from '@/models/user.js'
import Api from '@/models/api.js'
import Modal from '@/models/modal.js'

import AppButton from '@/components/buttons/AppButton.vue'
import AppLoader from '@/components/loader/LoaderView.vue'

import SelectView from '@/components/form/FormSelectView.vue'

store.commit('initialiseStore')

const app = createApp(App);

app.config.globalProperties.$toast = Toast
app.config.globalProperties.$user = User
app.config.globalProperties.$store = store
app.config.globalProperties.$api = Api
app.config.globalProperties.$modal = Modal

app.config.warnHandler = () => {}

app.use(store)
app.use(router)
app.use(vuelidate)

app.mixin({
    computed: {
        isLoggedIn() {
            return store.state.user.access_token != undefined
        },
        user() {
            return store.state.user
        }
    },
    methods: {
        makeid(length) {
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
               result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        },
        formatTime(duration) {
            var sec_num = parseInt(duration/1000, 10)

            var hours = Math.floor(sec_num/3600)+''
            var minutes = Math.floor((sec_num - (hours*3600)) / 60)+''
            var seconds = Math.floor(sec_num - (hours*3600) - (minutes*60))+''

            return (hours > 0 ? hours.padStart(2, '0')+':' : '')+minutes.padStart(2, '0')+':'+seconds.padStart(2, '0')
        }
    }
})

app.component('app-button', AppButton)
app.component('app-loader', AppLoader)
app.component('app-select', SelectView)

router.isReady().then(() => app.mount('#wrapper'))
