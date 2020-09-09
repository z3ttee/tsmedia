<template>
    <router-link :to="{name: 'watch', params: {id: entry.id}}" custom v-slot="{navigate}">
        <div class="video-list-item">
            <div class="video-thumbnail-wrapper" @click="navigate">
                <img :src="thumbnailURL" alt="">
                <div class="video-details-wrapper">
                    <span class="badge tiny">{{ category.name }}</span>
                    <span class="badge tiny">{{ formatTime(entry.duration) }}</span>
                </div>
            </div>
            <div class="video-info-wrapper">
                <div class="video-info-col creator-profile">
                    <img src="">
                </div>
                <div class="video-info-col">
                    <div class="video-info">
                        <h5 @click="navigate">{{ entry.title }}</h5>
                        <p><span>{{ creator.name }}</span></p>
                    </div>
                </div>
                <div class="video-info-col video-more">
                    <button class="btn btn-icon btn-tertiary btn-small"><img src="@/assets/images/icons/more.svg"></button>
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
export default {
    props: {
        entry: Object,
        creator: Object,
        category: Object
    },
    computed: {
        thumbnailURL() {
            return this.$store.state.config.api.baseURL+this.entry.thumbnail
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.video-list-item {
    width: 310px;
    margin-right: $innerPad;
}
.video-thumbnail-wrapper {
    position: relative;
    display: block;
    padding-top: 56.25%;
    overflow: hidden;
    border-radius: $borderRadSmall;
    cursor: pointer;

    &:last-of-type {
        margin: 0;
    }

    img {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        transition: all $animSpeedLong*1s $cubicNorm;
        border-radius: $borderRadSmall+2px;
    }

    &:hover {
        img {
            transform: scale(1.3) rotate(5deg);
        }
    }

    &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 110%;
        width: 100%;
        z-index: 10;
        background-color: rgba($color: $colorPrimary, $alpha: 0.4);
        background: linear-gradient(45deg, rgba($color: $colorPrimary, $alpha: 0.7) 20%, rgba($color: $colorPrimaryDark, $alpha: 0.2) 100%);
    }
}

.video-details-wrapper {
    position: absolute;
    z-index: 11;
    bottom: 0.3em;
    right: 0.3em;
}

.video-info-wrapper {
    display: table;
    width: 100%;
    padding: 0.5em 0.3em;
    table-layout: fixed;

    .video-info-col {
        display: table-cell;
        vertical-align: top;

        h5:hover {
            text-decoration: underline;
            cursor: pointer;
        }

        &.creator-profile {
            width: 40px;

            img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
            }
        }

        .video-info {
            display: block;
            padding: 0.2em 0.5em;
            
            width: 230px;
            white-space: normal;
            word-wrap: break-word;

            h5 {
                font-size: 0.95em;
                font-weight: 500;
                letter-spacing: 0px !important;
                line-height: 1.3em;
                word-wrap: break-word;
                margin-bottom: 0.2em;
            }

            p {
                font-size: 0.85em;
                
                span {
                    color: $colorAccent;
                    font-weight: 300;
                    opacity: 0.4;

                    &:hover {
                        cursor: pointer;
                        text-decoration: underline;
                    }
                }
            }
        }

        &.video-more {
            width: 32px;
        }
    }
}
</style>