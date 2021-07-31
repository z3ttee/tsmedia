<template>
  <wg-drawer-toolbar-layout>
    <template #toolbar>
      TSMedia
      <home-toolbar></home-toolbar>
    </template>
    <template #drawer>
      <app-home-sidebar></app-home-sidebar>
    </template>
    <template #content>
      <div class="content-container">
        <router-view></router-view>
      </div>
    </template>
  </wg-drawer-toolbar-layout>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgDrawerToolbarLayout } from "@tsalliance/wydget-vue"
import AppHomeSidebar from './views/sidebars/AppHomeSidebar.vue'
import HomeToolbar from './views/shared/HomeToolbar.vue'

export default defineComponent({
  components: {
    WgDrawerToolbarLayout,
    AppHomeSidebar,
    HomeToolbar
  }
})
</script>

<style lang="scss">
@import "@/assets/scss/style.scss";
</style>

<style lang="scss" scoped>
.content-container {
  padding: 0.8rem;
}
</style>
