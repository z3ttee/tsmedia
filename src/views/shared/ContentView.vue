<template>
    <div class="content-container">
        <app-action-bar></app-action-bar>
        <router-view></router-view>
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
    .content-container {
        background-color: $colorPrimaryDark;
    }
</style>