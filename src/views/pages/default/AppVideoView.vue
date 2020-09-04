<template>
    <section>
        <div class="layout-table">
            <div class="layout-col video-player-section">
                <appVideo :src="videoSrc"></appVideo>

                <div class="video-info-bar" v-if="video && video.category">
                    <span class="tag">{{ video.category.name }}</span>
                    <h4>{{ video.title }}</h4>
                    <p><span>{{ video.views }}</span> Aufrufe | Hochgeladen am <span>{{ new Date(parseInt(video.created)).toLocaleString() }}</span></p>
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
            <div class="layout-col recommended-section">
                <h5>Weitere Videos</h5>
            </div>
        </div>
    </section>
</template>

<script>
//import VideoListItem from '@/components/list/VideoListItem.vue'
import AppVideo from '@/components/video/AppVideo.vue'
import axios from 'axios';

export default {
    components: {
        AppVideo,
        //VideoListItem
    },
    data() {
        return {
            videoSrc: undefined,
            video: {}
        }
    },
    methods: {
        getData() {
            this.$api.get('video/'+this.$route.params.id).then((data) => {
                this.video = data

                var url = axios.defaults.baseURL+'video/watch/'+this.$route.params.id+'/?access_token='+this.$store.state.user.access_token
                this.videoSrc = url

                document.title = "TSMedia :: "+this.video.title
                console.log(data)
            }).finally(() => {
                //
                
            })
        }
    },
    mounted() {
        this.getData()
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.video-player-section {
    width: 80%;
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
</style>