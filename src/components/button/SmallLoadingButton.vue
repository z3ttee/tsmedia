<template>
    <button class="btn btn-primary btn-s" @click="clicked" :disabled="disabled">
        <app-loader class="loader" v-if="loading"></app-loader>
        <p v-html="text" :class="(!loading ? 'visible' : '')"></p>
    </button>
</template>

<script>
import AppLoader from '@/components/loader/PrimaryLoader.vue';

export default {
    props: ['text', 'identifier'],
    components: {
        AppLoader
    },
    data() {
        return {
            loading: false,
            disabled: false
        }
    },
    methods: {
        clicked() {
            this.loading = true
            this.disabled = true;
            this.$emit('click', event, this.done, this.identifier);
        },
        done() {
            this.loading = false
            this.disabled = false;
        }
    }
}
</script>

<style lang="scss" scoped>
button {
    position: relative;
    transition: all $animSpeedFast*1s $cubicNorm;

    p {
        display: inline-block;
        opacity: 0;
        transition: opacity $animSpeedFast*1s $cubicNorm;
        color: inherit;

        &.visible {
            opacity: 1;
        }
    }
}
.loader {
    position: absolute;
    width: 32px;
    height: 32px;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
}
</style>