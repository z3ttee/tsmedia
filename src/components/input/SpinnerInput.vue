<template>
    <div class="select-wrapper full">
        <select class="full" v-if="listItems.length > 0" :value="value" @input="change">
            <option v-for="(item, index) in listItems" :key="index" :selected="index==0">{{ item.name }}</option>
        </select>
        <select class="full" disabled v-else>
            <option selected>Loading...</option>
        </select>
        <span>
            <app-loader class="loader" v-if="listItems.length == 0"></app-loader>
            <img src="@/assets/images/icons/down.svg" v-else>
        </span>
    </div>
</template>

<script>
export default {
    props: ['items', 'value'],
    data() {
        return {
            listItems: []
        }
    },
    methods: {
        change(event) {
            this.$emit('input', event.target.value);
        }
    },
    mounted() {
        if(this.items && {}.toString.call(this.items) === '[object Function]') {
            this.items((result) => {
                console.log(result);
            });
        } else {
            this.listItems = this.items
        }
    }
}
</script>