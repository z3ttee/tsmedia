import { createRouter, createWebHistory } from 'vue-router'
import VueCookies from 'vue-cookies'
import routes from '@/router/routes.js'
import store from '@/store'
import user from '@/models/user.js'
import Toast from '@/models/toast.js'

const sessionCookieName = "ts_session";
const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(async (to, from, next) => {

  if(!VueCookies.isKey(sessionCookieName) && localStorage.getItem('data') != undefined) {
    localStorage.removeItem('data')
  }

  if(VueCookies.isKey(sessionCookieName) && !store.getters.isLoggedIn) {
    await user.checkLogin().catch((error) => {
      console.log(error)
    }).finally(() => {
      console.log('Session checked')
    })
  }

  if(to.meta.permission) {
    if(user.hasPermission(to.meta.permission)) {
      next()
      return
    } else {
      Toast.error('Keine Berechtigung diese Seite aufzurufen')

      console.log(router.currentRoute.value.meta.group);

      if(router.currentRoute.value.meta.group != 'default') {
        next({name: 'home'})
      } else {
        next(from)
      }
      return
    }
  }

  next()
}) 
router.afterEach((to) => {
    document.title = to.meta.title || "TSMedia";
    store.state.routeLoading = false;
});

export default router
