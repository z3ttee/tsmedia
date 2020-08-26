<template>
    <button @click.prevent="clicked">
        <span :style="'opacity: '+(loading ? '0' : '1')+';'">
            <slot></slot>
        </span>
        <lottie-player :src="loaderData" class="loader" speed="1" loop autoplay v-if="loading"></lottie-player>
    </button>
</template>

<script>
import AppLoader from '@/assets/animated/primary_loader_light.json';

export default {
    props: {
        payload: String
    },
    data() {
        return {
            loading: false,
        }
    },
    computed: {
        loaderData() {
            return JSON.stringify(AppLoader)
        }
    },
    methods: {
        clicked(event) {
            if(!this.loading){
                this.loading = true
                this.$emit('clicked', event, () => this.loading = false, this.payload)
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';
button {
    position: relative;

    span {
        transition: all $animSpeedFast*1s $cubicNorm;
    }
}
.loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    height: 2em !important;
    width: 2em !important;
}
</style>