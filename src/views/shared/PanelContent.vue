<template>
    <div class="content-container">
        <div id="contents">
            <app-loader v-if="loading" class="content-loader"></app-loader>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>
import AppLoader from '@/components/loader/PrimaryLoader.vue';
import EventBus from '@/models/selectedEventBus.js';

export default {
    components: {
        AppLoader
    },
    computed: {
        loading() {
            return this.$store.state.routeLoading;
        }
    },
    mounted() {
        EventBus.$on('actionSelected', (val) => {
            console.log(val);
        });
    }
}
</script>

<style lang="scss">
.content-loader {
    position: absolute;
    height: 45px;
    width: 45px;
    left: 55%;
    top: 50%;
    transform: translateY(-50%);
}
</style>

<style lang="scss" scoped>
    #contents {
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


    @media screen and (max-width: 840px) { 
        #contents {
            padding-top: 2.5em;
        }
    }
</style>