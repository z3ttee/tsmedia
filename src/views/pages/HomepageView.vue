<template>
    <div class="content-container">
        <section>
            <h4 class="content-control">Neuste Videos</h4>
            <app-message-box></app-message-box>

            <div class="list-container full">
                <div class="list-horizontal no-scrollbar">
                    <div class="item-wrapper large" v-for="(video, index) in latestVideos" :key="video.id+index">
                        <router-link :to="{path: '/view/'+video.id}" tag="div" class="item-thumbnail"><div></div></router-link>
                        <div class="item-details-bar">
                            <div class="item-title">
                                <router-link :to="{path: '/view/'+video.id}" tag="p" v-html="video.title"></router-link>
                                <p>{{ video.views }} Views</p>
                            </div>
                            <div class="item-actions">
                                <ul>
                                    <li><router-link :to="{path: '/view/'+video.id}" tag="button" class="btn btn-primary btn-icon" @click.prevent="watch(video.id)"><img src="@/assets/images/icons/eye.svg"></router-link></li>
                                    <li><button class="btn btn-primary btn-icon" @click.prevent="add(video.id)"><img src="@/assets/images/icons/add.svg"></button></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div></div><div></div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            latestVideos: [
                {
                    id: "123",
                    title: "Das ist ein Videotitel",
                    views: "100+",
                },
            ]
        }
    },
    methods: {
        watch() {

        },
        add() {

        }
    }
}
</script>