<template>
    <div class="modal dark">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="changelog-title">
                    <lottie-player class="animated-icon" src="https://assets9.lottiefiles.com/private_files/lf30_P2wyYh.json" speed="1" loop autoplay></lottie-player>
                    <h3><span>Eine neue Version ist verfügbar!</span>"{{ changelog.title }}"</h3>
                </div>
                <hr class="interface large">
                <p v-if="changelog.description">{{ changelog.description }}</p>
                <hr class="interface large" v-if="changelog.description">
                <div class="changelog-section" v-for="section in changelogSections" :key="section.title">
                    <h5>{{ section.title }}</h5>
                    <ul>
                        <li v-for="entry in section.log" :key="entry">{{ entry }}</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer dark">
                <button class="btn btn-accent btn-full" @click="$modal.dismiss()">OK</button>
            </div>
        </div>
    </div>
</template>

<script>
import Changelog from '@/../changelog.json'
import IconData from '@/assets/animated/congratulation.json'

import ModalEventListener from '@/events/ModalEventListener.js'

export default {
    setup() {
        return { calcModalContent }
    },
    props: {
        modal: Object
    },
    data() {
        return {
            iconData: IconData
        }
    },
    methods: {
        updateChangelog() {
            this.$store.commit('updateChangelog', this.changelog.versionCode)
        }
    },
    computed: {
        changelog() {
            return Changelog
        },
        changelogSections() {
            return this.changelog.sections.filter((element) => element.log.length > 0)
        }
    },
    mounted() {
        this.calcModalContent()
        window.addEventListener('resize', this.calcModalContent)
        ModalEventListener.on('dismiss', this.updateChangelog)
    },
    unmounted() {
        window.removeEventListener('resize', this.calcModalContent)
        ModalEventListener.off('dismiss', this.updateChangelog)
    }
}

function calcModalContent() {
    var footerHeight = document.getElementsByClassName('modal-footer')[0].getBoundingClientRect().height

    var modal = document.getElementsByClassName('modal')[0]
    var modalInfo = modal.getClientRects()[0]

    var contentHeight = modalInfo.height-footerHeight
    document.getElementsByClassName('modal-content')[0].style.height = contentHeight+'px'
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.modal {
    width: 500px;
    height: 80%;
    text-align: left;
}

.changelog-title {
    display: table;
    width: 100%;

    h3 {
        display: table-cell;
        vertical-align: middle;
        width: 100%;
        padding-left: 1em;

        span {
            color: $colorAccent;
            font-weight: 300;
            font-size: 0.6em;
            display: block;
        }
    }

    .animated-icon {
        vertical-align: top;
        display: table-cell;
        display: inline-block;
        height: 90px;
        width: 90px;
    }
}

p {
    opacity: 0.8;
}

.changelog-section {
    h5 {
        font-weight: 500;
        color: $colorAccent;
    }

    ul {
        display: block;
        margin-top: 0.5em;
        margin-left: 1em;

        li {
            position: relative;
            font-weight: 400;
            letter-spacing: 0.5px;
            font-size: 0.8em;
            margin-bottom: 0.5em;
            opacity: 0.8;
        }
    }
}
</style>