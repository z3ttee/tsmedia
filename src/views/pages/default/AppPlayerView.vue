<template>
    <div class="section">
        <app-loader v-if="!video"></app-loader>

        <div class="layout-table" v-if="video">
            <div class="layout-col video-player-section">
                <app-video-view :videoProp="video" @ended="nextVideo"></app-video-view>

                <div class="video-info-bar" v-if="video && video.category">
                    <span class="tag">{{ video.category.name }}</span>
                    <h4>{{ video.title }}</h4>
                    <p><span>{{ video.views }}</span> Aufrufe | Hochgeladen am <span>{{ new Date(parseInt(video.created)).toLocaleString().split(",")[0] }}</span></p>
                </div>
                <hr class="interface large">
                <div class="video-info-box" v-if="video && video.creator">
                    <img src="">
                    <h4>{{ video.creator.name }}</h4>
                </div>
                <hr class="interface large">
                <div class="video-info-description">
                    <h6>Videobeschreibung</h6>
                    <p>{{ video.description && video.description.length > 0 ? video.description : 'Keine Beschreibung' }}</p>
                </div>
            </div>
            <div class="layout-col videos-section">
                <div class="recommended-section autoplay-container">
                    <h5>Nächstes Video<span class="badge">Neu</span> <input type="checkbox" name="" id="" style="float: right; vertical-align: middle;" v-model="autoplayEnabled"></h5>
                    <app-loader v-if="videos.loading"></app-loader>
                    <expanded-video-list-item :video="autoplay" v-else></expanded-video-list-item>
                </div>
                <hr class="interface large">
                <div class="recommended-section more-videos">
                    <h5>Weitere Videos <span class="badge">Neu</span></h5>
                    <app-loader v-if="videos.loading"></app-loader>
                    <app-infinite-scroll-table @page="getVideos" :dataset="videos" :bottomReached="true" v-else>
                        <expanded-video-list-item v-for="video in videos.entries" :key="video.id" :video="video"></expanded-video-list-item>
                    </app-infinite-scroll-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppInfiniteScrollTable from '@/components/table/AppInfiniteScrollTable.vue'
import ExpandedVideoListItem from '@/components/list/ExpandedVideoListItem.vue'

import AppVideoView from '@/components/video/AppVideoView.vue'
import axios from 'axios';

export default {
    components: {
        AppVideoView,
        ExpandedVideoListItem,
        AppInfiniteScrollTable
    },
    computed: {
        autoplayEnabled: {
            set(val) {
                this.$store.commit('setAutoplay', val)
            },
            get() {
                return this.$store.state.autoplay
            }
        }
    },
    data() {
        return {
            videoSrc: undefined,
            video: undefined,
            autoplay: undefined,
            videos: {
                loading: true,
                entries: []
            }
        }
    },
    methods: {
        getData() {
            this.$api.get('video/'+this.$route.params.id).then((data) => {
                this.video = data

                var url = axios.defaults.baseURL+'video/watch/'+this.$route.params.id+'/?access_token='+this.$store.state.user.access_token
                this.videoSrc = url

                document.title = "TSMedia :: "+this.video.title
            })

            this.$api.get('video/recommended/').then((data) => {

                for(var index in data.entries) {
                    var video = data.entries[index]
                    video.creator = {
                        name: data.creators[video.creator].name
                    }
                }

                var autoplay = data.entries[0]
                data.entries.splice(0, 1)

                this.autoplay = autoplay
                this.videos = data
            }).finally(() => {
                this.videos.loading = false
            })
        },
        nextVideo() {
            if(this.$store.state.autoplay) {
                this.$router.push({name: 'watch', params: {id: this.autoplay.id}})
            }
        }
    },
    mounted() {
        this.getData()
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.section {
    width: 95%;
    transition: all $animSpeedQuick*1s $cubicNorm;
}

.video-player-section {
    width: 75%;
    padding-right: $innerPad;

    .video-info-bar {
        padding-top: 0.5em;

        h4 {
            font-weight: 500;
            padding: 0.5em 0;
        }

        p {
            font-weight: 300;
            font-size: 0.8em;
            opacity: 0.6;

            span {
                font-weight: 500;
            }
        }
    }

    .video-info-box {
        display: inline-block;
        width: 100%;
        height: 50px;
        line-height: 50px;

        h4 {
            display: inline-block;
            font-weight: 300;
            font-size: 0.85em;
            letter-spacing: 1px;
            padding-left: 0.5em;
        }

        img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            vertical-align: middle;
        }
    }

    .video-info-description {
        display: block;

        h6 {
            margin-bottom: 0.5em;
            letter-spacing: 0.5px;
        }
    }
}

.videos-section {
    max-width: 300px !important;
}

.recommended-section {
    margin-bottom: $innerPad;

    h5 {
        margin-bottom: 0.5em;
    }
}
</style>