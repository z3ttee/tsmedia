<template>
    <section>
        <wg-list class="navigation-list">
            <router-link :to="{name: 'home'}" custom v-slot="{ navigate, isExactActive}">
                <wg-list-item fa="fas fa-home" :active="isExactActive" @click="navigate">Startseite</wg-list-item>
            </router-link>
            <router-link :to="{name: 'discovery'}" custom v-slot="{ navigate, isExactActive}">
                <wg-list-item fa="fas fa-compass" :active="isExactActive" @click="navigate">Entdecken</wg-list-item>
            </router-link>
            <router-link :to="{name: 'gallery'}" custom v-slot="{ navigate, isExactActive}">
                <wg-list-item fa="fas fa-images" :active="isExactActive" @click="navigate">Gallerie</wg-list-item>
            </router-link>
        </wg-list>
        <wg-hr></wg-hr>
        <wg-list class="navigation-list" label="Bibliothek">
            <router-link :to="{name: 'libraryFavs'}" custom v-slot="{ navigate, isExactActive}">
                <wg-list-item fa="fas fa-star" :active="isExactActive" @click="navigate">Favoriten</wg-list-item>
            </router-link>
            <router-link :to="{name: 'libraryVideos'}" custom v-slot="{ navigate, isExactActive}">
                <wg-list-item fa="fas fa-video" :active="isExactActive" @click="navigate">Meine Videos</wg-list-item>
            </router-link>
            <router-link :to="{name: 'libraryImages'}" custom v-slot="{ navigate, isExactActive}">
                <wg-list-item fa="fas fa-image" :active="isExactActive" @click="navigate">Meine Bilder</wg-list-item>
            </router-link>
        </wg-list>
        <wg-hr></wg-hr>
        <wg-list class="navigation-list" label="Playlists">
            TODO
        </wg-list>
    </section>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgList, WgListItem, WgHr } from "@tsalliance/wydget-vue"

export default defineComponent({
    components: {
        WgList,
        WgListItem,
        WgHr
    }
})
</script>

<style lang="scss" scoped>
.navigation-list {
    padding: 0.8rem;
}
</style>
