<template>
    <div class="content-container">
        <app-action-bar></app-action-bar>
        <div id="contents">
            <app-loader v-if="loading" class="content-loader"></app-loader>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>
import AppActionBar from '@/views/shared/ActionbarView.vue'
import AppLoader from '@/components/loader/PrimaryLoader.vue';

export default {
    components: {
        AppActionBar,
        AppLoader
    },
    computed: {
        loading() {
            return this.$store.state.routeLoading;
        }
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
.content-loader-block {
    display: block;
    height: 45px;
    width: 45px;
}
</style>

<style lang="scss" scoped>
    @import '@/assets/scss/animations.scss';

    #contents {
        padding-top: 0em;
    }

    .content-container {
        background-color: $colorPrimaryDark;
    }


    @media screen and (max-width: 840px) { 
        #contents {
            padding-top: 2.5em;
        }
    }
</style>