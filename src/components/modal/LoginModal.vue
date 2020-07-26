<template>
    <div class="modal">
        <div class="modal-header">
            <p>{{ modal.data.title }}</p>
            <button class="btn btn-icon btn-tertiary" @click="dismissModal(modal.id)"><img src="@/assets/images/icons/close.svg"></button>
        </div>
        <div class="modal-content">
            <p>{{ modal.data.content }}</p>
        </div>
        <div class="modal-actions" v-if="modal.button">
            <button class="btn btn-tertiary btn-full" @click="modal.button.negative.callback || dismissModal(modal.id)" v-if="modal.button.negative">{{ modal.button.negative.text || 'OK' }}</button>
            <button class="btn btn-secondary btn-full">OK</button>
            <button class="btn btn-primary btn-full">OK</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['modal'],
    mounted() {
        console.log(this.modal);
    }
}
</script>