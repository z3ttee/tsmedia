<template>
    <div id="sidebar" :class="{'layout-col sidebar-wrapper': true, 'large': routeGroup == 'panel'}">
        <transition mode="out-in" name="slideLeft" :appear="false">
            <app-sidebar-view v-if="routeGroup == 'default' || routeGroup == 'studio'"></app-sidebar-view>
            <panel-sidebar-view v-else></panel-sidebar-view>
        </transition>
    </div>
    <div id="contents" class="layout-col">
        
        <div id="content-wrapper" class="content-wrapper">
            <app-actionbar-view></app-actionbar-view>
            <router-view v-slot="{Component}">
                <component :is="Component" :key="$route.fullPath"></component>
            </router-view>
        </div>
    </div>

    <!--<div id="taskbar">
        <div class="taskbar-wrapper">
            <p>Videos werden hochgeladen... <span>0 / 7</span></p>
            <button class="btn btn-dark btn-small">Weitere Informationen</button>
            <button class="btn btn-dark btn-small">Alle abbrechen</button>
        </div>
    </div>-->
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
import Changelog from '@/../changelog.json'

import AppSidebarView from '@/views/shared/sidebars/AppSidebarView.vue';
import PanelSidebarView from '@/views/shared/sidebars/PanelSidebarView.vue';
import AppActionbarView from '@/views/shared/AppActionbarView.vue';

import AppToast from '@/components/message/Toast.vue';
import AppModal from '@/components/modal/AppModal.vue';

export default {
    setup() {
        return { calcContentWidth }
    },
    components: {
        AppSidebarView,
        AppActionbarView,
        PanelSidebarView,
        AppToast,
        AppModal
    },
    computed: {
        toast() {
            var toast = this.$store.state.toast;
            return toast;
        },
        modal() {
            var modal = this.$store.state.modal;
            return modal;
        },
        routeGroup(){
            var group = this.$route.meta.group;
            return group;
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
        this.calcContentWidth();
        window.addEventListener('resize', this.calcContentWidth)

        if(this.$store.state.changelog < Changelog.versionCode) {
            this.$modal.changelog()
        }
    }
}

function calcContentWidth() {
    var sidebarWidth = document.getElementById('sidebar').getBoundingClientRect().width
    var contentWrapper = document.getElementById('content-wrapper')

    var contentWidth = window.innerWidth-sidebarWidth
    contentWrapper.style.width = contentWidth+'px'

    var contents = document.getElementById('contents');
    var contentsPad = window.getComputedStyle(contents).paddingRight;
    contentsPad = parseInt(contentsPad.replace('px', ''));

    contents.style.paddingLeft = (contentsPad+sidebarWidth)+'px'
}
</script>

<style lang="scss">
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/animations.scss';
@import '@/assets/scss/style.scss';
@import '@/assets/scss/forms.scss';
</style>
