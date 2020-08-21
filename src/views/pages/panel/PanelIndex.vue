<template>
    <router-view v-slot="{Component}">
        <transition mode="out-in" name="slideUp">
            <component :is="Component" :key="$route.fullPath"></component>
        </transition>
    </router-view>
</template>

<style lang="scss">
@import '@/assets/scss/interface.scss';
</style>