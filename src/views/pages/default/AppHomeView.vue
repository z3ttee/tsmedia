<template>
    <app-horizontal-table headline="Neuste Videos" :itemWidth="320">
        <video-list-item v-for="video in latestVideos.entries.videos" :key="video.id" :entry="video" :creator="latestVideos.entries.creators[video.creator]" :category="latestVideos.entries.categories[video.category]"></video-list-item>
    </app-horizontal-table>
    <br><br>
    <app-infinite-scroll-table headline="Alle Videos" @page="getVideos" :dataset="videos">
        <video-list-item v-for="video in videos.entries" :key="video.id" :entry="video" :creator="videos.creators[video.creator]" :category="videos.categories[video.category]"></video-list-item>
    </app-infinite-scroll-table>
</template>

<script>
import VideoListItem from '@/components/list/VideoListItem.vue'
import AppHorizontalTable from '@/components/table/AppHorizontalTable.vue'
import AppInfiniteScrollTable from '@/components/table/AppInfiniteScrollTable.vue'

export default {
    components: {
        VideoListItem,
        AppHorizontalTable,
        AppInfiniteScrollTable
    },
    data() {
        return {
            latestVideos: {
                loading: true,
                entries: {}
            },
            videos: {
                entries: []
            }
        }
    },
    methods: {
        getLatest() {
            this.$api.get('video/latest/').then((data) => {
                this.latestVideos.entries = data
            }).finally(() => {
                this.latestVideos.loading = false
            })
        },
        getVideos(offset = 0, limit = 15, done = () => {}) {
            console.log('getting videos')
            this.$api.get('video/all/?order=shuffled&offset='+offset+'&limit='+limit).then((data) => {
                this.videos = data
                console.log(this.videos.entries)
            }).finally(() => {
                done()
            })
        },
    },
    mounted() {
        this.getLatest()
        //this.getVideos()

        
    }
}

</script>

<style lang="scss">
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';
</style>