<template>
    <router-view v-slot="{Component}">
        <transition mode="out-in" name="slideUp">
            <component :is="Component"></component>
        </transition>
    </router-view>
</template>