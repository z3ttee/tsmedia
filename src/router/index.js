import { createRouter, createWebHistory } from 'vue-router'
import routes from '@/router/routes.js';
import store from '@/store'

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(() => {
    store.state.routeLoading = true;
}) 
router.afterEach((to) => {
    document.title = to.meta.title || "TSMedia";
    store.state.routeLoading = false;
});

export default router
