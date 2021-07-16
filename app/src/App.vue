<template>
  <wg-drawer>
    <template #drawer>
      Sidebar
    </template>
    <template #content>
      Content
      <router-view></router-view>
    </template>
  </wg-drawer>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgDrawer } from "@tsalliance/wydget-vue"

export default defineComponent({
  components: {
    WgDrawer
  }
})
</script>

<style lang="scss">
@import "@/assets/scss/style.scss";
</style>
