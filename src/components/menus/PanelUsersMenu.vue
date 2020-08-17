<template>
    <div class="sidebar-actions-container">
        <router-link :to="{name: 'panelUsers'}" custom v-slot="{navigate, isExactActive}">
            <button :class="{'btn btn-darker btn-full btn-align-left': true, 'active': isExactActive} " @click="navigate">Übersicht anzeigen</button>
        </router-link>
    </div>
</template>

<script>
export default {
    
}
</script>