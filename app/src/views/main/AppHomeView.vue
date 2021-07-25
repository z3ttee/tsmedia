<template>
    <section>
        <div class="grid-wrapper">
            <wg-card class="grid-item card-text" :clickable="true" v-for="i of [1,2,3,4,5,6,7,8,9,10]" :key="i">
                <template #media><wg-image></wg-image></template>
                <template #title>Videotitel {{ i }}</template>
                <template #subtitle>Kekdef</template>
            </wg-card>
        </div>
    </section>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { WgCard, WgImage } from "@tsalliance/wydget-vue"

export default defineComponent({
    components: {
        WgCard,
        WgImage
    }
})
</script>

<style lang="scss" scoped>
.grid-wrapper {
    display: grid;
    grid-template-columns: 20% 20% 20% 20% 20%;
    grid-template-rows: auto;
}
.grid-item {
    width: 100%;
    padding: 0.4rem;
}
</style>
