import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import AppHomeView from "@/views/main/AppHomeView.vue";
import AppDiscoveryView from "@/views/main/AppDiscoveryView.vue";
import AppGalleryView from "@/views/main/AppGalleryView.vue";

const routes: Array<RouteRecordRaw> = [
    { path: "/", name: "home", component: AppHomeView },
    { path: "/discover", name: "discovery", component: AppDiscoveryView },
    { path: "/gallery", name: "gallery", component: AppGalleryView },
    { path: "/library", name: "libraryFavs", component: () => import("@/views/library/AppFavoritesView.vue") },
    { path: "/library/videos", name: "libraryVideos", component: () => import("@/views/library/AppLibraryVideosView.vue") },
    { path: "/library/images", name: "libraryImages", component: () => import("@/views/library/AppLibraryImagesView.vue") }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
