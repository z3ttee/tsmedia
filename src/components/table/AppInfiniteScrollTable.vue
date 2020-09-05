<template>
    <div class="infinite-scroll-wrapper">
        <div class="infinite-scroll-head">
            <span v-show="headline">{{ headline }}</span>
        </div>

        <div class="infinite-scroll-container">
            <slot></slot>
        </div>

        <app-loader v-if="loading"></app-loader>
        <br>
        <button class="btn btn-light btn-med" v-if="!loading && canPaginate">Mehr laden</button>
    </div>
</template>

<script>
export default {
    props: {
        headline: String,
        dataset: Object,
        bottomReached: Boolean,
        maxPages: Number
    },
    setup() {
        return { getOffset, getLimit }
    },
    data() {
        return {
            loading: true,
            maxEntries: 15,
            currentPage: 1
        }
    },
    methods: {
        onscroll(event) {
            if(this.canPaginate()) {
                var isBottomOfWindow = Math.trunc(event.target.scrollHeight-event.target.clientHeight-event.target.scrollTop) <= 400
                if(isBottomOfWindow && !this.loading) {
                    this.emitPage()
                }
            }
        },
        entries() {
            return this.dataset ? this.dataset.entries || [] : []
        },
        canPaginate(){
            return this.maxPages && this.maxPages <= this.currentPage || !this.bottomReached
        },
        isEmpty() {
            if(this.loading) return false
            if(!this.entries) return true
            if(this.entries.length > 0) return false
            return true
        },
        emitPage() {
            this.loading = true

            console.log()

            this.$emit('page', this.getOffset(this.currentPage, this.maxEntries), this.getLimit(this.currentPage, this.maxEntries), () => { 
                this.loading = false 
                this.currentPage += 1
            })
        }
    },
    mounted() {
        document.getElementById('wrapper').addEventListener('scroll', this.onscroll)
        this.emitPage()
    },
    unmounted() {
        document.getElementById('wrapper').removeEventListener('scroll', this.onscroll)
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

.info {
    text-align: center;
    padding: $innerPad;
    font-weight: 300;
    font-size: 0.7em;
    letter-spacing: 0.3px;
    opacity: 0.2;
}
</style>