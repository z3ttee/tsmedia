<template>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th class="table-select" v-if="columns && columns.length > 0"><input class="select" type="checkbox" @change="selectAll" :checked="entries.length > 0 && selected == entries.length"></th>
                    <th v-for="col in columns" :key="col">{{ col }}</th>
                </tr>
            </thead>

            <tbody>
                <slot></slot>
            </tbody>
        </table>

        <app-loader v-if="loading"></app-loader>
        <p class="msg-box" v-if="isEmpty">Keine Einträge gefunden.</p>

        <div :class="{'pagination': true, 'disabled': !paginate}">
            <span>{{ maxEntries }} Ergebnisse pro Seite</span>
            <span>{{ currentPage }} von {{ maxPages }}</span>
            <app-button class="btn btn-pagination" title="Erste Seite" @clicked="first" :disabled="currentPage == 1"><img src="@/assets/images/icons/next.svg" style="transform: rotate(180deg);"></app-button>
            <app-button class="btn btn-pagination" title="Vorherige" @clicked="prev" :disabled="currentPage == 1"><img src="@/assets/images/icons/down.svg" style="transform: rotate(90deg);"></app-button>
            <app-button class="btn btn-pagination" title="Nächste" @clicked="next" :disabled="currentPage == maxPages"><img src="@/assets/images/icons/down.svg" style="transform: rotate(-90deg);"></app-button>
            <app-button class="btn btn-pagination" title="Letzte Seite" @clicked="last" :disabled="currentPage == maxPages"><img src="@/assets/images/icons/next.svg"></app-button>
        </div>
    </div>
</template>

<script>
export default {
    setup() {
        return { getOffset, getLimit }
    },
    props: {
        columns: Array,
        data: Number,
        loading: Boolean
    },
    data() {
        return {
            maxEntries: 15,
            currentPage: 1
        }
    },  
    computed: {
        available() {
            return this.data.available
        },
        entries() {
            return this.data.entries
        },
        selected() {
            var filtered = []
            for(var selected in this.data.selected ) {
                if(this.data.selected[selected]) filtered.push(selected)
            }
            return filtered.length
        },
        maxPages() {
            var pages = Math.ceil(this.available / this.maxEntries)
            return pages
        },
        paginate(){
            return this.maxPages > 1
        },
        isEmpty() {
            if(this.loading) return false
            if(!this.entries) return true
            if(this.entries.length > 0) return false
            return true
        }
    },
    methods: {
        next(event, done) {
            if(this.currentPage < this.maxPages) {
                this.currentPage++
                this.$emit('page', getOffset(this.currentPage, this.maxEntries), getLimit(this.currentPage, this.maxEntries), done )
            }
            done()
        },
        prev(event, done) {
            if(this.currentPage > 1) {
                this.currentPage--
                this.$emit('page', getOffset(this.currentPage, this.maxEntries), getLimit(this.currentPage, this.maxEntries), done )
            }
            done()
        },
        first(event, done) {
            if(this.currentPage > 1) {
                this.currentPage = 1
                this.$emit('page', getOffset(this.currentPage, this.maxEntries), getLimit(this.currentPage, this.maxEntries), done )
            }
            done()
        },
        last(event, done) {
            if(this.currentPage < this.maxPages) {
                this.currentPage = this.maxPages
                this.$emit('page', getOffset(this.currentPage, this.maxEntries), getLimit(this.currentPage, this.maxEntries), done )
            }
            done()
        },
        selectAll(event) {
            this.$emit('select', event.target.checked)
        }
    },
    mounted() {
        this.$emit('page', getOffset(this.currentPage, this.maxEntries), getLimit(this.currentPage, this.maxEntries), () => {} )
    }
}

function getOffset(current, max) {
    return (current-1)*max || 0
}
function getLimit(current, max) {
    return (current*max) || 1
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';

.table-select {
    width: 1em;
    text-align: center !important;
}
</style>