<template>
    <div class="video-container">
        <video :src="src" controls autoplay></video>
    </div>
</template>

<script>
export default {
    props: {
        src: String,
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.video-container {
    position: relative;
    display: block;
    padding-top: 56.25%;
    overflow: hidden;
    border-radius: $borderRadSmall;
    cursor: pointer;

    video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
}
</style>