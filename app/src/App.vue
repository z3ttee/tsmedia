<template>
<div>
  <wg-button><i class="fas fa-ad"></i> Hello</wg-button>
<router-view></router-view>
</div>
  
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgButton } from "alliance-wydget-vue"

export default defineComponent({
  components: {
    WgButton
  }
})
</script>


<style lang="scss">
@import "@/assets/scss/style.scss";
</style>
