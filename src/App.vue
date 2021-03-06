<template>
    <app-actionbar-view></app-actionbar-view>
    <div class="app-layout-col">
        <div class="layout-col-wrapper sidebar-wrapper" id="sidebar">
            <transition mode="out-in" name="slideLeft" :appear="false">
                <app-sidebar-view v-if="routeGroup == 'default' || routeGroup == 'studio'"></app-sidebar-view>
                <panel-sidebar-view v-else></panel-sidebar-view>
            </transition>
        </div>
    </div>
    <div class="app-layout-col">
        <div id="contents" class="layout-col-wrapper content-wrapper">
            <app-content-header></app-content-header>
            <router-view v-slot="{Component}">
                <transition mode="out-in" name="slideLeft" :appear="false">
                    <component :is="Component" :key="$route.fullPath"></component>
                </transition>
            </router-view>
        </div>
    </div>

    <transition name="toast" mode="out-in" :appear="true">
        <div id="toast-container" v-if="toast">
            <app-toast :toast="toast" :key="toast.id"></app-toast>
        </div>
    </transition>
    <transition name="modalScale" mode="out-in" :appear="true">
        <div id="modal-container" v-if="modal" @click="$modal.modalClicked">
            <app-modal :modal="modal" :key="modal.id"></app-modal>
        </div>
    </transition>
</template>

<script>
import SidebarEventListener from '@/events/SidebarEventListener.js'

import Changelog from '@/../changelog.json'

import AppSidebarView from '@/views/shared/sidebars/AppSidebarView.vue'
import PanelSidebarView from '@/views/shared/sidebars/PanelSidebarView.vue'
import AppActionbarView from '@/views/shared/AppActionbarView.vue'
import AppContentHeader from '@/components/header/AppContentHeader.vue'

import AppToast from '@/components/message/Toast.vue'
import AppModal from '@/components/modal/AppModal.vue'

export default {
    setup() {
        return { calcContentWidth }
    },
    components: {
        AppSidebarView,
        AppActionbarView,
        PanelSidebarView,
        AppContentHeader,
        AppToast,
        AppModal
    },
    computed: {
        toast() {
            var toast = this.$store.state.toast
            return toast
        },
        modal() {
            var modal = this.$store.state.modal
            return modal
        },
        routeGroup(){
            var group = this.$route.meta.group
            return group
        }
    },
    watch: {
        // TODO: Search optimised way -> performance issue because on every navigation contents will be recalculated
        $route() {
            setTimeout(() => {
                this.calcContentWidth()
            }, 450); // 450 millis because of sidebar reveal anim duration
        }
    },
    mounted() {
        this.calcContentWidth()
        window.addEventListener('resize', this.calcContentWidth)

        if(this.$store.state.changelog < Changelog.versionCode) {
            this.$modal.changelog()
        }

        SidebarEventListener.on('toggle', () => setTimeout(this.calcContentWidth, 301))
    }
}

function calcContentWidth() {
    var sidebar = document.getElementsByClassName('layout-col-wrapper')[0]
    var content = document.getElementsByClassName('layout-col-wrapper')[1]
    var actionbarHeight = document.getElementById('actionbar').getBoundingClientRect().height

    var contentPadTop = 32
    var contentHeight = window.innerHeight+"px"

    sidebar.style.height = contentHeight
    sidebar.style.paddingTop = actionbarHeight+contentPadTop+"px";
    content.style.height = contentHeight
    content.style.paddingTop = actionbarHeight+contentPadTop+"px";

    content.style.width = (window.innerWidth-sidebar.getBoundingClientRect().width)+"px"
}
</script>

<style lang="scss">
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/animations.scss';
@import '@/assets/scss/style.scss';
@import '@/assets/scss/forms.scss';
</style>
