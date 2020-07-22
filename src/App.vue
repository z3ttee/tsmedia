<template>
    <app-layout></app-layout>
</template>

<script>
import AppLayout from '@/views/layouts/Layout.vue';

export default {
    components: {
        AppLayout
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/fonts.scss';

/*
[]=========== General styling ===========[]
*/

* {
    margin: 0 auto;
    padding: 0;
    box-sizing: border-box;
    color: $colorWhite;
}

html, body {
    height: 100%;
    width: 100%;
    font-family: 'Poppins';
    font-weight: 500;
}

#wrapper {
    display: block;
    position: relative;
    overflow: hidden;
    min-height: 100%;
    width: 100%;
}

/*
[]=========== Headlines ===========[]
*/
h1, h2, h3, h4, h5, h6 {
    font-family: 'Poppins';
    font-weight: 800;
}
h1 {
    font-size: 3em;
}
h2 {
    font-size: 2.3em;
}
h3 {
    font-size: 1.65em;
}
h4 {
    font-size: 1.3em;
}
h5 {
    font-size: 1.15em;
}
h6 {
    font-size: 0.9em;
    font-weight: 500;
}

/*
[]=========== Buttons ===========[]
*/
.btn {
    appearance: none;
    border: none;
    background: none;
    outline: none;
    font-family: 'Poppins';
    font-weight: 500;
    font-size: 0.8em;
    transition: all $animSpeedQuick*1s $cubicNorm;
    padding: 0.8em;
    cursor: pointer;
    border-radius: $borderRadSmall;

    img {
        vertical-align: middle;
        margin-right: 0.3em;
    }

    &:active {
        transform: scale(0.96);
    }

    &.btn-primary {
        background-color: $colorAccent;
    }

    &.btn-m {
        padding: 0.8em 1.5em;
    }
    &.btn-l {
        padding: 0.8em 2.3em;
    }

    &.btn-icon {
        background-color: $colorPlaceholder;

        img {
            margin: 0;
        }
    }
}

/*
[]=========== Headlines ===========[]
*/
.box {
    background-color: $colorPlaceholder;
    border-radius: $borderRadNormal;
    box-shadow: $shadowNormal;
}

/*
[]=========== Horizontal lines ===========[]
*/
hr {
    &.interface {
        appearance: none;
        border: none;
        height: 3px;
        width: 50%;
        margin: 0 auto;
        background-color: $colorPlaceholder;
        opacity: 0.4;
        border-radius: 4px;
        margin: 1em auto;

        &.large {
            width: 100%;
        }
    }
}
</style>