import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store';

Vue.use(VueRouter)

const routes = [
    { path: '*', redirect: {name: '404'} },
    { path: '/', name: 'Home', component: () => import(/* webpackChunkName: "home" */ '@/views/pages/HomepageView.vue') },
    { path: '/categories', name: 'Categories', component: () => import(/* webpackChunkName: "categories" */ '@/views/pages/CategoriesView.vue') },
    { path: '/panel', name: 'Panel', component: () => import(/* webpackChunkName: "dashboard" */ '@/views/pages/panel/DashboardView.vue') },
    { path: '/account', name: 'Account', component: () => import(/* webpackChunkName: "account" */ '@/views/pages/AccountView.vue') },
    { path: '/404', name: '404', component: () => import(/* webpackChunkName: "404" */ '@/views/pages/404View.vue') }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
});

router.beforeEach((to, from, next) => {
    store.state.routeLoading = true;
    next();
});
router.afterEach(() => {
    store.state.routeLoading = false;
})

export default router