<template>
    <component :is="modalComp" :modal="modal"></component>
</template>
<script>
import { defineAsyncComponent } from 'vue'

export default {
    props: {
        modal: Object
    },
    computed: {
        modalComp() {
            var AsyncModal = undefined;

            if(this.modal.action == 'login') {
                AsyncModal = defineAsyncComponent({
                    loader: () => import('@/components/modal/LoginModal.vue')
                });
            }

            return AsyncModal
        }
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/_variables.scss';

.modal {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
    background-color: $colorPlaceholder;
    border-radius: $borderRadSmall;
    overflow: hidden;
    word-wrap: break-word;
}

.modal-header {
    display: inline-block;
    width: 100%;
    background-color: $colorPrimary;
    padding: $innerPad/2 $innerPad;

    p {
        display: inline-block;
        font-weight: 700;
        font-size: 0.9em;
        vertical-align: middle;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    button {
        float: right;
        width: 30px;
        height: 30px;
        padding: 0;

        img {
            width: 10px;
            height: 10px;
        }
    }
}
.modal-actions {
    display: block;
    padding: $innerPad/2 $innerPad $innerPad $innerPad;
}
.modal-content {
    padding: $innerPad;
}
</style>