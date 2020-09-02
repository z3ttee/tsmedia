<template>
    <div class="horizontal-scroll-container" :style="'width: '+(width ? width+'px' : '100%')">
        <div class="scroll-item-wrapper">
            <h1>Das ist ein Item</h1>
            <p>Das ist Inhalt</p>
        </div>
        <div class="scroll-item-wrapper">
            <h1>Das ist ein Item</h1>
            <p>Das ist Inhalt</p>
        </div>
        <div class="scroll-item-wrapper">
            <h1>Das ist ein Item</h1>
            <p>Das ist Inhalt</p>
        </div>
        <div class="scroll-item-wrapper">
            <h1>Das ist ein Item</h1>
            <p>Das ist Inhalt</p>
        </div>
        <div class="scroll-item-wrapper">
            <h1>Das ist ein Item</h1>
            <p>Das ist Inhalt</p>
        </div>
        <div class="scroll-item-wrapper">
            <h1>Das ist ein Item</h1>
            <p>Das ist Inhalt</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        width: Number
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.horizontal-scroll-container {
    white-space: nowrap;
    overflow-x: auto;
    overflow-y: hidden;

    padding-left: $innerPad;

    -ms-overflow-style: none;
    scrollbar-width: none;

    &::-webkit-scrollbar {
        display: none;
    }

    .scroll-item-wrapper {
        display: inline-block;
        padding: 14px;
    }
}
</style>