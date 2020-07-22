<template>
    <div id="wrapper">
        <app-sidebar class="layout-col"></app-sidebar>
        <app-content class="layout-col"></app-content>
    </div>
</template>

<script>
import AppSidebar from '@/views/shared/SidebarView.vue';
import AppContent from '@/views/shared/ContentView.vue';

export default {
    components: {
        AppSidebar,
        AppContent
    }
}
</script>

<style lang="scss" scoped>
    #wrapper {
        display: table;
    }

    .layout-col {
        display: table-cell;
        vertical-align: top;
        padding: 1.5em;

        &:first-of-type {
            padding: 0;
            padding-top: 1.5em;
        }
    }
</style>