import { createRouter, createWebHistory } from 'vue-router'
import routes from '@/router/routes.js';

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.afterEach((to) => {
    document.title = to.meta.title || "TSMedia";
});

export default router
