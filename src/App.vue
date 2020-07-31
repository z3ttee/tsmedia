<template>
    <app-layout></app-layout>
</template>

<script>
import AppLayout from '@/views/layouts/Layout.vue';

export default {
    components: {
        AppLayout
    },
    created() {
        this.showNotice({
            title: 'Das ist ein Titel',
            content: 'Das ist der Inhalt',
            duration: 10000000,
            type: 'success'
        });
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/fonts.scss';
@import '@/assets/scss/style.scss';
@import '@/assets/scss/modal.scss';
</style>