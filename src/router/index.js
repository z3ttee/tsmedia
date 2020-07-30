import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store';

Vue.use(VueRouter)

const routes = [
    { path: '*', redirect: {name: '404'} },
    { path: '/', name: 'Home', component: () => import(/* webpackChunkName: "home" */ '@/views/pages/HomepageView.vue') },
    { path: '/categories', name: 'Categories', component: () => import(/* webpackChunkName: "categories" */ '@/views/pages/CategoriesView.vue') },
    { path: '/panel', name: 'Panel', redirect: {name: 'Dashboard'}, component: () => import(/* webpackChunkName: "panelContent" */ '@/views/shared/PanelContent.vue'), meta: { group: 'panel' }, children: [
        { path: '', name: 'Dashboard', component: () => import(/* webpackChunkName: "dashboard" */ '@/views/pages/panel/DashboardView.vue'), meta: { group: 'panel', actions: 'DashboardActions' }},
        { path: 'users/', name: 'Users', component: () => import(/* webpackChunkName: "userindex" */ '@/views/pages/panel/user/UserIndex.vue'), meta: { group: 'panel', actions: 'UserActions' }}
    ]},
    { path: '/account', name: 'Account', component: () => import(/* webpackChunkName: "account" */ '@/views/pages/AccountView.vue') },
    { path: '/profile/:id', name: 'ProfileView', component: () => import(/* webpackChunkName: "account" */ '@/views/pages/AccountView.vue') },
    { path: '/view/:id', name: 'MediaView', component: () => import(/* webpackChunkName: "account" */ '@/views/pages/MediaViewerView.vue') },
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