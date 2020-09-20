<template>
    <div class="section">
        <video-carousel-list-view :dataset="latestVideos"></video-carousel-list-view>
        <app-infinite-scroll-table headline="Alle Videos" @page="getVideos" :dataset="videos" :bottomReached="infiniteBottomReached">
            <video-list-item classes="item-medium" v-for="video in videos.entries" :key="video.id" :entry="video" :creator="videos.creators[video.creator]" :category="videos.categories[video.category]"></video-list-item>
        </app-infinite-scroll-table>
    </div>
</template>

<script>
import VideoCarouselListView from '@/components/list/VideoCarouselListView.vue'
import VideoListItem from '@/components/list/VideoListItem.vue'
import AppInfiniteScrollTable from '@/components/table/AppInfiniteScrollTable.vue'

export default {
    components: {
        VideoListItem,
        AppInfiniteScrollTable,
        VideoCarouselListView
    },
    data() {
        return {
            latestVideos: {
                entries: [],
                creators: [],
                categories: []
            },
            videos: {
                entries: [],
                creators: [],
                categories: []
            },
            infiniteBottomReached: false
        }
    },
    methods: {
        getLatest() {
            this.$api.get('video/latest/').then((data) => {
                this.latestVideos = data
            }).finally(() => {
                this.latestVideos.loading = false
            })
        },
        getVideos(offset = 0, limit = 15, done = () => {}) {
            this.$api.get('video/all/?order=shuffled&offset='+offset+'&limit='+limit).then((data) => {
                this.videos.entries = this.videos.entries.concat(data.entries)

                this.videos.creators = data.creators
                this.videos.categories = data.categories
            }).catch(() => {
                this.infiniteBottomReached = true
            }).finally(() => {
                done()
            })
        },
    },
    mounted() {
        this.getLatest()
    }
}

</script>

<style lang="scss">
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';
</style>