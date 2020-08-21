<template>
    <div class="select-wrapper">
        <app-loader class="select-loader" v-if="list.length == 0"></app-loader>
        <select @change="change" :style="{'opacity: 0;': list.length == 0}" :disabled="list.length == 0">
            <option v-for="(item, index) in list" :key="index" :value="item.id" :selected="item.id == modelValue">{{ item.name }}</option>
        </select>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: String,
        list: Array
    },
    data() {
        return {
            value: undefined
        }
    },
    watch: {
        list() {
            if(this.list[0] && !this.modelValue) {
                this.value = this.list[0].id
                this.change()
            }
        }
    },
    methods: {
        change(){
            this.$emit('update:modelValue', this.value)
        }
    },
    mounted() {
        if(this.list[0]) {
            this.value = this.list[0].id
        }
    }
}
</script>