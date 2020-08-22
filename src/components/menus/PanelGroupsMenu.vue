<template>
    <div class="sidebar-actions-container">
        <router-link :to="{name: 'panelGroups'}" custom v-slot="{navigate, isExactActive}">
            <button :class="{'btn btn-darker btn-full btn-align-left': true, 'active': isExactActive} " @click="navigate">Übersicht anzeigen</button>
        </router-link>
        <router-link :to="{name: 'panelGroupsEditor', params: { id: 'new' }}" custom v-slot="{navigate, isExactActive}" v-if="$store.getters.hasPermission('permission.groups.create')">
            <button :class="{'btn btn-darker btn-full btn-align-left': true, 'active': isExactActive} " @click="navigate">Gruppe erstellen</button>
        </router-link>
    </div>
</template>

<script>
export default {
    
}
</script>