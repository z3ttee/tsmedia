<template>
    <div class="app-content-header" :style="'height: '+(height ? height+'px' : '40%' )">
        <div class="app-content-header-wrapper"></div>
    </div>
</template>

<script>
export default {
    props: {
        height: Number
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.app-content-header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 200px;
    z-index: -1;

    .app-content-header-wrapper {
        position: relative;
        display: block;
        width: 100%;
        height: 100%;

        background: url('~@/assets/images/background/background_blurred.webp'), 
                    url('~@/assets/images/background/background_blurred.png'),
                    $colorPrimaryDark;
        background-position: center;
        background-size: cover;
        background-attachment: fixed;

        &::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            height: $innerPad*3;
            width: 100%;
            z-index: -1;
            background: linear-gradient(0deg, $colorPrimaryDark 0%, transparent 100%);
        }
    }
}
</style>