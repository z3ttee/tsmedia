<template>
    <div class="modal">
        <div class="modal-header">
            <p>{{ data.title }}</p>
            <button class="btn btn-icon btn-tertiary" @click="closeModal"><img src="@/assets/images/icons/close.svg"></button>
        </div>
        <div class="modal-content">
            <p>{{ data.content }}</p>
        </div>
        <div class="modal-actions">
            <button class="btn btn-primary btn-full">OK</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['data'],
    methods: {
        closeModal(){

        }
    }
}
</script>