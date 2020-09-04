<template>
    <div>
        <div class="interface-head">
            <h2>Deine Uploads</h2>
            <hr class="interface large">
        </div>

        <div class="queue" v-if="Object.keys(uploads).length > 0">
            <h5>Uploads in Warteschlange</h5>
            <div class="msg-box" v-for="upload in uploads" :key="upload.id">
                {{ upload.name }} {{ upload.progress }}%

                <div class="queue-actions">
                    <button class="btn btn-dark" @click="upload.cancel" v-if="!upload.error">Abbrechen</button>
                    <button class="btn btn-dark" @click="upload.retry" v-if="upload.error">Wiederholen</button>
                    <button class="btn btn-accent" @click="upload.delete" v-if="upload.error">Löschen</button>
                </div>
                <p>Error: {{ upload.error }}</p>
                <app-progress-bar class="progress-col" :progress="upload.progress">{{ upload.progress == 100 ? 'Video hochgeladen' : 'wird hochgeladen...' }}</app-progress-bar>
            </div>

            <hr class="interface large">
        </div>

        <app-table-view :columns="['Video', 'Sichtbarkeit', 'Datum', 'Aufrufe', 'Favs', ' ']" :dataset="videos" :loading="loading" @page="getData" @select="selectAll" @delete="remove">
            <tr v-for="video in videos.entries" :key="video.id">
                <td><input class="select" type="checkbox" :value="video.id" v-model="videos.selected[video.id]"></td>
                <td>
                    <div class="video">
                        <div class="video-col"><img :src="video.thumbnail"><span class="badge small botr">{{ formatTime(video.duration) }}</span></div>
                        <div class="video-col">
                            <p>{{ video.title }}</p>
                            <p>{{ video.description }}</p>
                        </div>
                    </div>
                </td>
                <td>{{ getVisibility(video.visibility) }}</td>
                <td>{{ new Date(parseInt(video.created)).toLocaleDateString() }}</td>
                <td>{{ video.views }}</td>
                <td>{{ video.favs }}</td>
                <td>
                    <button class="btn btn-light">Bearbeiten</button>
                    <app-button class="btn btn-accent" @clicked="remove" :payload="video.id">Löschen</app-button>
                </td>
            </tr>
        </app-table-view>
    </div>
</template>

<script>
import AppProgressBar from '@/components/progress/AppProgressBar.vue'
import AppTableView from '@/components/table/AppTableView.vue'
import UploadEventListener from '@/events/UploadEventListener.js'

export default {
    components: {
        AppTableView,
        AppProgressBar
    },
    data() {
        return {
            videos: {
                selected: {},
                entries: []
            },
            loading: true
        }
    },
    computed: {
        uploads() {
            return this.$store.state.uploads
        }
    },
    methods: {
        edit() {

        },
        remove(event, done, data) {
            if(data == 'selected') {
                var entries = this.videos.entries.filter((element) => this.videos.selected[element.id])
                var ids = entries.map((element) => element.id)

                this.$api.delete('video/?byIDs='+JSON.stringify(ids), {}).then(() => {
                    for(var entry of entries) {
                        var index = this.videos.entries.indexOf(entry)
                        this.videos.entries.splice(index, 1)
                        delete this.videos.selected[entry.id]
                    }
                    this.$toast.success('Ausgewählte Uploads gelöscht')
                }).finally(() => {
                    done()
                })
            } else {
                var video = this.videos.entries.filter((element) => element.id == data)[0]
                var index = this.videos.entries.indexOf(video)

                this.$api.delete('video/'+data, {}).then(() => {
                    this.videos.entries.splice(index, 1)
                    this.$toast.success('Video gelöscht')
                }).finally(() => {
                    done()
                })
            }
        },
        selectAll(checked) {
            var ids = this.videos.entries.map((element) => {
                return element.id
            })

            for(var id of ids) {
                this.videos.selected[id] = checked
            }
        },
        getData(offset = 0, limit = 15, done = () => {}){
            this.loading = true
            this.videos = {
                selected: {},
                entries: []
            }

            this.$api.get('video/ofuser/?offset='+offset+'&limit='+limit).then((data) => {
                this.videos = {...this.videos, ...data}
            }).finally(() => {
                this.loading = false
                done()
            })
        },
        getVisibility(visibility) {
            switch (parseInt(visibility)) {
                case 1:
                    return 'Privat'
                case 2:
                    return 'Nicht gelistet'
                case 3:
                    return 'Öffentlich'
                default:
                    return 'Wird verarbeitet...'
            }
        }
    },
    mounted() {
        UploadEventListener.on('oncomplete', () => {
            setTimeout(this.getData, 300)
        })
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';

.video {
    display: inline-block;
    text-align: left;

    .video-col {
        float: left;
        vertical-align: middle;
        overflow: hidden;

        &:first-of-type {
            position: relative;
            width: 110px;
            height: 62px;
            margin-right: 0.5em;
            border-radius: $borderRadTiny;
            
            img {
                width: 100%;
                height: 100%;
            }
        }
        &:last-of-type {
            letter-spacing: 0.3px;
            max-height: 80px;
            overflow: hidden;

            p {
                display: block;
                width: 150px;
                overflow: hidden;

                &:first-of-type {
                    white-space: nowrap;
                    text-overflow: ellipsis;
                }
                &:last-of-type {
                    opacity: 0.4;
                    font-size: 0.75em;
                    font-weight: 300;
                }
            }
        }

        & > div {
            width: 100%;
            height: 100%;
            background-position: center;
            background-size: cover;
        }
    }
}
</style>