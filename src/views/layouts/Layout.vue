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
        max-width: 100%;
    }

    .layout-col {
        display: table-cell;
        vertical-align: top;
        height: 100%;
        overflow: hidden;

        &:first-of-type {
            padding: 0;
        }
    }

    @media screen and (max-width: 840px) {
        #wrapper {
            display: block !important;
            background-color: $colorPrimaryDark;
        }
        .layout-col {
            overflow: unset !important;
            display: block !important;
            height: initial !important;
        }
    }
</style>