import { createRouter, createWebHistory } from 'vue-router'
import routes from '@/router/routes.js';
import store from '@/store'
import user from '../models/user';
import VueCookies from 'vue-cookies'

const sessionCookieName = "ts_session";
const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(async (to, from, next) => {
  if(localStorage.getItem('data') != undefined) {
    localStorage.removeItem('data')
  }

  if(VueCookies.isKey(sessionCookieName) && !store.getters.isLoggedIn) {
    await user.checkLogin().catch((error) => {
      console.log(error)
    })
  }

  if(to.meta.permission) {
    if(user.hasPermission()) {
      next()
    } else {
      next(from)
    }
  }

  next()
}) 
router.afterEach((to) => {
    document.title = to.meta.title || "TSMedia";
    store.state.routeLoading = false;
});

export default router
