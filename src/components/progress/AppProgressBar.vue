<template>
    <div class="progress-box">
        <div class="progress-bar"><div :id="elementID" class="inner"></div></div>
        <div class="progress-info">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    setup() {
        return { setProgress }
    },
    props: {
        progress: Number
    },
    data() {
        return {
            elementID: this.makeid(6),
        }
    },
    watch: {
        progress(val) {
            this.setProgress(val)
        }
    },
    mounted() {
        this.setProgress(this.progress)
    }
}

function setProgress(progress) {
    document.getElementById(this.elementID).style.width = progress+'%'
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.progress-box {

    .progress-bar {
        position: relative;
        display: block;
        height: 0.3em;
        background-color: $colorPlaceholder;
        border-radius: 3px;
        overflow: hidden;

        .inner {
            display: inline-block;
            height: 100%;
            vertical-align: top;
            background-color: $colorAccent;
            transition: all $animSpeedFast*1s linear;
            border-radius: 3px;
        }
    }

    .progress-info {
        text-align: center;
        font-size: 0.65em;
        font-weight: 300;
        letter-spacing: 1px;
        padding: 0.5em;
    }
}
</style>