<template>
    <div class="modal">
        <div class="modal-header">
            <p>{{ modal.data.title }}</p>
            <button class="btn btn-icon btn-tertiary" @click="dismissModal(modal.id)"><img src="@/assets/images/icons/close.svg"></button>
        </div>
        <div class="modal-content">
            <p>{{ modal.data.content }}</p>
        </div>
        <div class="modal-actions" v-if="modal.buttons">
            <button class="btn btn-primary btn-full" v-if="modal.buttons.neutral" v-html="modal.buttons.neutral.text" @click="(modal.buttons.neutral.action ? modal.buttons.neutral.action() : dismissModal(modal.id))"></button>
            <button class="btn btn-primary btn-full" v-if="modal.buttons.negative" v-html="modal.buttons.negative.text" @click="(modal.buttons.negative.action ? modal.buttons.negative.action() : dismissModal(modal.id))"></button>
            <button class="btn btn-primary btn-full" v-if="modal.buttons.positive" v-html="modal.buttons.positive.text" @click="(modal.buttons.positive.action ? modal.buttons.positive.action() : dismissModal(modal.id))"></button>
        </div>
        <div class="modal-actions" v-else>
            <button class="btn btn-primary btn-full" @click="dismissModal(modal.id)">OK</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['modal']
}
</script>