<template>
    <section>
        <div class="grid-wrapper">
            <wg-card class="grid-item" title="Videotitle" subtitle="Subtitel">
                <template #title>Videotitel</template>
                <template #subtitle>Videotitel</template>
            </wg-card>
        </div>
    </section>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgCard } from "@tsalliance/wydget-vue"

export default defineComponent({
    components: {
        WgCard
    }
})
</script>

<style lang="scss" scoped>
.grid-item {
    width: 100%;
}
</style>
