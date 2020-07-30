<template>
    <lottie-player class="loader" :src="loaderData" :options="options"></lottie-player>
</template>

<script>
import AppLoader from '@/assets/animated/primary_loader_light.json';

export default {
    props: ["settings"],
    data() {
        return {
            loaderData: AppLoader
        }
    },
    computed: {
        options() {
            var options = this.settings ?? {};

            if(!options.loop) options.loop = true;
            if(!options.autoplay) options.autoplay = true;

            return options
        }
    }
}
</script>