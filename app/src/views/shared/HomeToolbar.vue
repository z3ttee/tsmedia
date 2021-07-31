<template>
    <div class="home-toolbar">
        <wg-button><i class="fas fa-upload"></i>Upload</wg-button>
        <wg-avatar class="avatar-circular avatar-m" src="" :clickable="true"></wg-avatar>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgAvatar, WgButton } from "@tsalliance/wydget-vue"

import UploadModalImpl from "@/components/modals/UploadModalImpl.vue"

export default defineComponent({
    components: {
        WgAvatar,
        WgButton
    },
    methods: {
        showUploadModal() {
            this.$modals.show(UploadModalImpl, {
                
            })
        }
    }
})
</script>

<style lang="scss" scoped>
.home-toolbar {
    float: right;
    vertical-align: middle;
}
</style>