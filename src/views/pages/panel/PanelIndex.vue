<template>
    <router-view v-slot="{Component}">
        <component :is="Component" :key="$route.fullPath"></component>
    </router-view>
</template>

<style lang="scss">
@import '@/assets/scss/interface.scss';
</style>