<template>
        <h3>Neuste Videos</h3>
        <app-horizontal-table :width="$store.state.metrics.contentWidth"></app-horizontal-table>
</template>

<script>
import AppHorizontalTable from '@/components/table/AppHorizontalTable.vue'

export default {
    components: {
        AppHorizontalTable
    },
    data() {

    }
}

</script>