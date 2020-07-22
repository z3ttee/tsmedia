<template>
    <div class="content-container">
        <app-action-bar></app-action-bar>
        <div id="contents">
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>
import AppActionBar from '@/views/shared/ActionbarView.vue'

export default {
    components: {
        AppActionBar
    }
}
</script>

<style lang="scss" scoped>
    #contents {
        padding: 2.5em;
        padding-top: 0em;
    }

    .content-container {
        background-color: $colorPrimaryDark;
    }

    .fade-enter-active {
        animation: fadeIn $animSpeedNormal*1s $cubicNorm forwards;
    }
    .fade-leave-active {
        animation: fadeOut $animSpeedNormal*1s $cubicNorm forwards;
    }
    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    @keyframes fadeOut {
        0% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        100% {
            opacity: 0;
            transform: translateY(10px);
        }
    }
</style>