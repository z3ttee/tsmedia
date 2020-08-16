const appTitlePrefix = "TSMedia :: "
import AppHomeView from '@/views/pages/default/AppHomeView.vue'

export default [
    { path: '/', name: 'home', component: AppHomeView, meta: { title: appTitlePrefix+'Startseite', group: 'default' } },
    { path: '/categories', name: 'categories', component: AppHomeView, meta: { title: appTitlePrefix+'Kategorien', group: 'default' } },
    { path: '/library', name: 'library', component: AppHomeView, meta: { title: appTitlePrefix+'Mediathek', group: 'default' } },
    { path: '/studio', name: 'studio', component: AppHomeView, meta: { title: appTitlePrefix+'Studio', group: 'studio' } },
    { path: '/panel', name: 'panel', component: () => import('@/views/pages/panel/PanelIndex.vue'), meta: { title: appTitlePrefix+'Dashboard', group: 'panel' }, children: [
        { path: '', name: 'panelDashboard', component: () => import('@/views/pages/panel/groups/PanelGroupIndex.vue'), meta: { title: appTitlePrefix+'Dashboard', group: 'panel' }},
        { path: 'groups/', name: 'panelGroups', component: () => import('@/views/pages/panel/groups/PanelGroupIndex.vue'), meta: { title: appTitlePrefix+'Berechtigungen', group: 'panel' }}
    ]}

]