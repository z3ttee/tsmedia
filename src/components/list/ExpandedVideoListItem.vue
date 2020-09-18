<template>
    <router-link :to="{name: 'watch', params: {id: video.id}}" custom v-slot="{navigate}">
        <div class="expanded-list-item">
            <div class="item-col">
                <div class="thumbnail-wrapper" @click="navigate">
                    <img :src="thumbnailURL" alt="">
                </div>
            </div>
            <div class="item-col">
                <div class="video-info" :id="infoElID">
                    <h6 :id="titleElId" @click="navigate">{{ video.title }}</h6>
                    <ul>
                        <li>{{ video.creator.name }}</li>
                        <li>{{ video.views }} Aufrufe</li>
                    </ul>
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
import clamp from 'clamp-js'

export default {
    props: {
        video: Object
    },
    data() {
        return {
            infoElID: this.makeid(6),
            titleElId: this.makeid(6)
        }
    },
    computed: {
        thumbnailURL() {
            return this.$store.state.config.api.baseURL+this.video.thumbnail
        }
    },
    mounted() {
        var videoTitle = document.getElementById(this.titleElId)
        clamp(videoTitle, {clamp: 2,useNativeClamp: true})
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.expanded-list-item {
    display: table;
    width: 100%;
    position: relative;
    background: linear-gradient(45deg, $colorPrimaryDark 50%, rgba(53,59,68,1) 100%);
    border-radius: $borderRadSmall;
    overflow: hidden;
    border: 2px solid $colorPlaceholder;
    margin: 0.5em 0;

    .item-col {
        display: table-cell;
        vertical-align: top;

        &:first-of-type {
            width: 50%;
        }
    }
}

.thumbnail-wrapper {
    position: relative;
    display: block;
    padding-top: 56.25%;
    padding-left: 56.25%;
    overflow: hidden;
    border-top-right-radius: $borderRadSmall;
    border-bottom-right-radius: $borderRadSmall;
    cursor: pointer;
    min-height: 100px;
    
    img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        min-height: 100px;
        transition: all $animSpeedLong*1s $cubicNorm;
    }

    &:hover {
        img {
            transform: scale(1.2);
        }
    }
}

.video-info {
    padding: 0.5em 0.5em;

    h6 {
        font-size: 0.8em;
        font-weight: 500;
        word-break: break-all;

        &:hover {
            text-decoration: underline;
            cursor: pointer;
        }
    }

    ul {
        list-style: none;
        padding: 0;
        margin-top: 0.5em;

        li {
            font-size: 0.7em;
            font-weight: 300;
            white-space: nowrap;
        }
    }
}
</style>