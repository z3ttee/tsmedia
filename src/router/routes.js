const appTitlePrefix = "TSMedia :: "
import AppHomeView from '@/views/pages/default/AppHomeView.vue'

export default [
    { path: '/', name: 'home', component: AppHomeView, meta: { title: appTitlePrefix+'Startseite', group: 'default' } },
    { path: '/categories', name: 'categories', component: AppHomeView, meta: { title: appTitlePrefix+'Kategorien', group: 'default' } },
    { path: '/library', name: 'library', component: AppHomeView, meta: { title: appTitlePrefix+'Mediathek', group: 'default' } },
    { path: '/studio', name: 'studio', component: AppHomeView, meta: { title: appTitlePrefix+'Studio', group: 'studio' } },
    { path: '/panel', name: 'panel', redirect: {name: 'panelDashboard'}, component: () => import('@/views/pages/panel/PanelIndex.vue'), meta: { title: appTitlePrefix+'Dashboard', group: 'panel', permission: 'permission.panel' }, children: [
        { path: '', name: 'panelDashboard', component: () => import('@/views/pages/panel/PanelDashboard.vue'), meta: { title: appTitlePrefix+'Dashboard', group: 'panel', menu: 'PanelDashboardMenu', permission: 'permission.panel' }},
        
        { path: 'users/', name: 'panelUsers', component: () => import('@/views/pages/panel/users/PanelUserIndex.vue'), meta: { title: appTitlePrefix+'Benutzerübersicht', group: 'panel', menu: 'PanelUsersMenu', permission: 'permission.users' }},
        { path: 'users/editor/:id', name: 'panelUsersEditor', component: () => import('@/views/pages/panel/users/PanelUserEditor.vue'), meta: { title: appTitlePrefix+'Benutzer bearbeiten', group: 'panel', menu: 'PanelUsersMenu' }},

        { path: 'groups/', name: 'panelGroups', component: () => import('@/views/pages/panel/groups/PanelGroupIndex.vue'), meta: { title: appTitlePrefix+'Berechtigungen', group: 'panel', menu: 'PanelGroupsMenu', permission: 'permission.groups' }},
        { path: 'groups/editor/:id', name: 'panelGroupsEditor', component: () => import('@/views/pages/panel/groups/PanelGroupIndex.vue'), meta: { title: appTitlePrefix+'Berechtigungen', group: 'panel', menu: 'PanelGroupsMenu' }}
    ]}
]