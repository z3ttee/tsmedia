<template>
    <div class="horizontal-scroll-wrapper">
        <div class="horizontal-scroll-head">
            <span v-show="headline">{{ headline }}</span>
            <div class="scroll-buttons">
                <button class="btn btn-icon btn-tertiary" @click="scrollPrev"><img src="@/assets/images/icons/down.svg" style="transform: rotate(90deg);"></button>
                <button class="btn btn-icon btn-tertiary" @click="scrollNext"><img src="@/assets/images/icons/down.svg" style="transform: rotate(-90deg);"></button>
            </div>
        </div>

        <div class="horizontal-scroll-container" :style="'width: '+(width ? width+'px' : '100%')">
            <slot></slot>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        width: Number,
        headline: String,
        itemWidth: Number
    },
    methods: {
        scrollNext() {
            var element = document.getElementsByClassName('horizontal-scroll-container')[0]
            element.scrollTo({
                top: 0,
                left: element.scrollLeft+(this.itemWidth ? this.itemWidth : 250),
                behavior: 'smooth'
            })
        },
        scrollPrev() {
            var element = document.getElementsByClassName('horizontal-scroll-container')[0]
            element.scrollTo({
                top: 0,
                left: element.scrollLeft-(this.itemWidth ? this.itemWidth : 250),
                behavior: 'smooth'
            })
        }
    }
}

</script>