<template>
    <div id="wrapper">
        <transition name="modalContainerFade" mode="out-in" appear>
            <div id="modal-container" v-if="modals.length > 0" @click="containerClicked">
                <transition-group name="modalSlide" appear>
                    <component v-for="modal in modals" :key="modal.id" :is="modal.component" :modal="modal"></component>
                </transition-group>
            </div>
        </transition>

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
    },
    computed: {
        modals() {
            var modals = this.$store.state.activeModals;
            return modals;
        }
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

    #modal-container {
        position: absolute;
        z-index: 100000;
        width: 100%;
        height: 100%;
        background-color: rgba($color: black, $alpha: 0.7);
    }

    /*.scale-enter {
        animation: scaleIn $animSpeedNormal*1s $cubicNorm;
    }
    .fade-enter-active {
        animation: fadeIn $animSpeedNormal*1s $cubicNorm;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }*/

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