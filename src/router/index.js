import Vue from 'vue'
import store from '@/store/index.js'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
    { path: '/', name: 'Home', component: () => import(/* webpackChunkName: "home" */ '@/views/pages/HomepageView.vue') },
    { path: 'categories/', name: 'Categories', component: () => import(/* webpackChunkName: "categories" */ '@/views/pages/CategoriesView.vue') },
    { path: 'panel/', name: 'Panel', component: () => import(/* webpackChunkName: "dashboard" */ '@/views/pages/panel/DashboardView.vue') }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
});

router.beforeEach((to, from, next) => {
    store

    next();
});

export default router