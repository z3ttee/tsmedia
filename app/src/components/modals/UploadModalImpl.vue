<template>
    <wg-base-modal :modal="modal" :fullscreen="true">
        


    </wg-base-modal>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgBaseModal } from "@tsalliance/wydget-vue"

export default defineComponent({
    components: {
        WgBaseModal
    },
    props: {
        modal: {
            type: Object,
            required: true
        }
    }
})
</script>
