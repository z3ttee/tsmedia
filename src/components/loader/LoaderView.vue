<template>
    <lottie-player :src="loaderData" speed="1" loop autoplay></lottie-player>
</template>

<script>
import AppLoader from '@/assets/animated/primary_loader_light.json';

export default {
    computed: {
        loaderData() {
            return JSON.stringify(AppLoader)
        }
    }
}
</script>

<style lang="scss" scoped>
lottie-player {
    height: 24px;
    width: 24px;
}
</style>