<template>
    <div class="carousel-container" :id="containerID">
        <div v-for="(data) in dataset.entries" :key="data.id" class="carousel-item-wrapper">
            <router-link :to="{name: 'watch', params: {id: data.id}}" custom v-slot="{navigate}">
                <div class="carousel-item">
                    <div class="carousel-col">
                        <div class="video-container" @click="navigate">
                            <img :src="thumbnailURL(data.thumbnail)" alt="">
                            <div class="video-badge-wrapper">
                                <span class="badge tiny">{{ formatTime(data.duration) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-col video-info-box">
                        <div class="info-box-profile pressable-l">
                            <img src=""> {{ dataset.creators[data.creator].name }}
                        </div>
                        <div class="info-box-details">
                            <span class="badge tiny pressable-l">{{ dataset.categories[data.category].name }}</span>
                            <h5 @click="navigate">{{ data.title }}</h5>
                            <p v-if="data.description" @click="navigate">{{ data.description }}</p>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
    </div>
    <div class="carousel-scroll-footer">
        <div class="scroll-buttons">
            <button class="btn btn-icon btn-tertiary" @click="srcoll(-1)"><img src="@/assets/images/icons/down.svg" style="transform: rotate(90deg);"></button>
            <button class="btn btn-icon btn-tertiary" @click="srcoll(1)"><img src="@/assets/images/icons/down.svg" style="transform: rotate(-90deg);"></button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        dataset: Object
    },
    data() {
        return {
            containerID: "id"+this.makeid(16),
            currentIndex: 0
        }
    },
    methods: {
        thumbnailURL(thumbnail) {
            return this.$store.state.config.api.baseURL+thumbnail
        },
        srcoll(direction) {
            this.currentIndex = this.currentIndex+direction

            var element = document.getElementById(this.containerID)
            var firstItem = element.childNodes[2]
            var style = window.getComputedStyle(firstItem)
            var offset = parseInt(style.width.replace('px', ''))+parseInt(style.marginRight.replace('px', ''))

            element.scrollTo({
                top: 0,
                left: element.scrollLeft+(offset*direction),
                behavior: 'smooth'
            })
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

$videoContainerHeight: 220px;
$videoContainerWidth: 391.11px;
$infoContainerWidth: 200px;

.carousel-scroll-footer {
    display: block;
    text-align: center;
    margin-bottom: $innerPad;
}

.carousel-container {
    display: block;
    position: relative;
    white-space: nowrap;
    overflow-y: hidden;
    overflow-x: auto;
    margin-top: $innerPad;

    -ms-overflow-style: none;
    scrollbar-width: none;

    &:first-of-type {
        margin-top: 0;
    }

    &::-webkit-scrollbar {
        display: none;
    }

    .carousel-item-wrapper {
        position: relative;
        display: inline-block;
        width: $videoContainerWidth+$infoContainerWidth;
        height: $videoContainerHeight;
        margin-right: $innerPad/2;
        z-index: 0;
    }

    .carousel-item {
        display: table;
        height: 100%;
        width: 100%;
        border-radius: $borderRadSmall;
        overflow: hidden;
        background: linear-gradient(45deg, $colorPrimaryDark 0%, rgba(53,59,68,1) 100%);
        transition: all $animSpeedNormal*1s $cubicNorm;
        border: 2px solid $colorPlaceholder;
    }

    .carousel-col {
        display: table-cell;
        vertical-align: top;
        height: $videoContainerHeight;
        overflow: hidden;

        &:first-of-type {
            width: $videoContainerWidth;
        }
        &:last-of-type {
            width: $infoContainerWidth;
        }

        &.video-info-box {
            position: relative;
            display: block;
            padding: 1.5em;

            .info-box-profile {
                display: inline-block;
                width: 100%;
                font-size: 0.7em;
                letter-spacing: 1px;
                font-weight: 300;
                word-break: break-all;
                cursor: pointer;

                img {
                    height: 32px;
                    width: 32px;
                    border-radius: 50%;
                    vertical-align: middle;
                    margin-right: 0.5em;
                }
            }

            .info-box-details {
                position: relative;
                margin-top: 1em;
                display: block;

                .badge {
                    margin: 0;
                    margin-bottom: 1em;
                    cursor: pointer;
                }

                h5 {
                    font-weight: 500;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    max-width: $infoContainerWidth;
                    overflow: hidden;

                    &:hover {
                        text-decoration: underline;
                        cursor: pointer;
                    }
                }

                p {
                    display: block;
                    word-wrap: break-word;
                    margin-top: 1em;
                    font-weight: 500;
                    font-size: 0.8em;
                    opacity: 0.7;
                }
            }

            &::after {
                position: absolute;
                content: '';
                bottom: 0;
                left: 0;
                width: 100%;
                height: 3em;
                background: linear-gradient(00deg, $colorPrimary 0%, transparent 100%);
                z-index: 10;
            }
        }
    }

    .video-container {
        position: relative;
        width: 100%;
        height: 100%;
        background-color: $colorPlaceholder;
        cursor: pointer;

        border-top-right-radius: $borderRadSmall;
        border-bottom-right-radius: $borderRadSmall;
        overflow: hidden;

        img {
            display: block;
            width: 100%;
            height: 100%;
            transition: all $animSpeedNormal*1s $cubicNorm;
        }

        .video-badge-wrapper {
            position: absolute;
            right: 0.5em;
            bottom: 0.5em;
        }

        &:hover {
            img {
                transform: scale(1.1);
            }
        }
    }
}
</style>