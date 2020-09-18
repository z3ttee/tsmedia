<template>
    <div class="video-container">
        <video :poster="thumbnailURL" controls @error="onVideoError" v-if="error.code == 0" @ended="onVideoEnded">
            <source :src="source" type="video/mp4">
            Your browser does not support html5 video
        </video>
        <div class="video-error" v-else>
            <h3>Whoops, da ist etwas schief gelaufen!</h3>
            <p>{{ error.message }}</p>
            <div class="video-error-buttons">
                <button class="btn btn-dark" @click="$modal.login({name: 'watch', params: {id: video.id}})"><img src="@/assets/images/icons/key.svg" alt="">Anmelden</button>
                <button class="btn btn-dark" @click="$router.go()"><img src="@/assets/images/icons/refresh.svg" alt="">Seite neuladen</button>
            </div>
        </div>
        <div class="next-countdown" v-if="$store.state.autoplay && next.ready">
            <h4>Nächstes Video in</h4>
            <h1>{{ next.seconds }}</h1>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        videoProp: {
            type: Object,
        }
    },
    data() {
        return {
            loading: true,
            error: {
                code: 0,
                message: ''
            },
            video: this.videoProp,
            next: {
                ready: false,
                seconds: 10,
                interval: undefined
            }
        }
    },
    watch: {
        videoProp(val) {
            this.loading = false
            this.error.code = 0
            this.video = val
        },
        next(val) {
            console.log(val)
        }
    },
    computed: {
        source() {
            if(this.$user.isLoggedIn()) {
                return axios.defaults.baseURL+'video/watch/'+this.video.id+'/?access_token='+this.$store.state.user.access_token
            } else {
                return ''
            }
        },
        thumbnailURL() {
            return this.$store.state.config.api.baseURL+this.video.thumbnail
        }
    },
    methods: {
        onVideoError(event) {
            var errrorCode = event.target.error ? event.target.error.code : -1
            this.error.code = errrorCode

            switch(errrorCode) {
                case 1: //MEDIA_ERR_ABORTED 
                    this.error.message = 'Ladevorgang von Benutzer abgebrochen'
                    break
                case 2: //MEDIA_ERR_NETWORK 
                    this.error.message = 'Bitte überprüfe deine Internetverbindung'
                    break
                case 3: //MEDIA_ERR_DECODE 
                    this.error.message = 'Beim Dekodieren des Videos ist ein Fehler aufgetreten'
                    break
                case 4: //MEDIA_ERR_SRC_NOT_SUPPORTED 
                    this.error.message = 'Keine der verfügbaren Quellen wird von deinem Browser unterstützt'
                    break

                default:
                    this.error.message = 'Bevor du ein Video ansehen kannst, musst du dich anmelden'
                    break
            }
        },
        onVideoEnded() {
            if(this.$store.state.autoplay) {
                this.next.ready = true
                this.next.interval = setInterval(() => {
                    this.next.seconds--

                    if(this.next.seconds == 0) {
                        this.$emit('ended')
                        clearInterval(this.next.interval)
                    }
                }, 1000)
            }
        }
    },
    mounted() {
        this.loading = Object.keys(this.videoProp).length == 0
    },
    unmounted() {
        clearInterval(this.next.interval)
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
    //cursor: pointer;

    video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
}

.video-error {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    text-align: center;

    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: $colorPlaceholder;

    h3 {
        font-weight: 500;
        color: $colorAccent;
    }

    p {
        display: block;
        width: 100%;
        padding: 0em $innerPad;
    }

    button {
        margin-right: 1em;
        margin-top: $innerPad;

        &:last-of-type {
            margin-right: 0;
        }
    }
}

.next-countdown {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba($color: black, $alpha: 0.6);
    z-index: 100;

    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    text-align: center;
}
</style>