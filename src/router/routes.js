const appTitlePrefix = "TSMedia :: "

import AppHomeView from '@/views/pages/default/AppHomeView.vue'
import AppPlayerView from '@/views/pages/default/AppPlayerView.vue'
import AppResultsView from '@/views/pages/default/AppResultsView.vue'

export default [
    { path: '/', name: 'home', component: AppHomeView, meta: { title: appTitlePrefix+'Startseite', group: 'default' } },
    { path: '/results/:query', name: 'searchResults', component: AppResultsView, meta: { title: appTitlePrefix+'Suche', group: 'default' } },
    { path: '/categories', name: 'categories', component: AppHomeView, meta: { title: appTitlePrefix+'Kategorien', group: 'default' } },
    { path: '/watch/:id', name: 'watch', component: AppPlayerView, meta: { title: appTitlePrefix, group: 'default' } },
    { path: '/library', name: 'library', component: AppHomeView, meta: { title: appTitlePrefix+'Mediathek', group: 'default' } },
    { path: '/panel', name: 'panelDashboard', component: () => import('@/views/pages/panel/PanelDashboard.vue'), meta: { title: appTitlePrefix+'Dashboard', group: 'panel', permission: 'permission.panel' }},

    { path: '/panel/users/', name: 'panelUsers', component: () => import('@/views/pages/panel/users/PanelUserIndex.vue'), meta: { title: appTitlePrefix+'Benutzerübersicht', group: 'panel', menu: 'PanelUsersMenu', permission: 'permission.users' }},
    { path: '/panel/users/editor/:id', name: 'panelUsersEditor', component: () => import('@/views/pages/panel/users/PanelUserEditor.vue'), meta: { title: appTitlePrefix+'Benutzer bearbeiten', group: 'panel', menu: 'PanelUsersMenu', permission: 'permission.users' }},
    
    { path: '/panel/groups/', name: 'panelGroups', component: () => import('@/views/pages/panel/groups/PanelGroupIndex.vue'), meta: { title: appTitlePrefix+'Berechtigungen', group: 'panel', menu: 'PanelGroupsMenu', permission: 'permission.groups' }},
    { path: '/panel/groups/editor/:id', name: 'panelGroupsEditor', component: () => import('@/views/pages/panel/groups/PanelGroupEditor.vue'), meta: { title: appTitlePrefix+'Berechtigungen', group: 'panel', menu: 'PanelGroupsMenu', permission: 'permission.groups' }},

    { path: '/studio', name: 'studio', component: () => import('@/views/pages/studio/UploadIndex.vue'), meta: { title: appTitlePrefix+'Deine Uploads', group: 'studio' }},
]