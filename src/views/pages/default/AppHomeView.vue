<template>
    <app-horizontal-table headline="Neuste Videos" :itemWidth="320">
        <video-list-item v-for="video in latestVideos.entries.videos" :key="video.id" :entry="video" :creator="latestVideos.entries.creators[video.creator]" :category="latestVideos.entries.categories[video.category]"></video-list-item>
        
    </app-horizontal-table>
</template>

<script>
import VideoListItem from '@/components/list/VideoListItem.vue'
import AppHorizontalTable from '@/components/table/AppHorizontalTable.vue'

export default {
    components: {
        VideoListItem,
        AppHorizontalTable
    },
    data() {
        return {
            latestVideos: {
                loading: true,
                entries: {}
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
        }
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