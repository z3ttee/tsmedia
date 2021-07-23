<template>
  <wg-drawer-toolbar-layout>
    <template #toolbar>
      TSMedia
    </template>
    <template #drawer>
      Drawer
    </template>
    <template #content>
      <router-view></router-view>
    </template>
  </wg-drawer-toolbar-layout>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgDrawerToolbarLayout } from "@tsalliance/wydget-vue"

export default defineComponent({
  components: {
    WgDrawerToolbarLayout
  }
})
</script>

<style lang="scss">
@import "@/assets/scss/style.scss";
</style>
