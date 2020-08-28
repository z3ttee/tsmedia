<template>
    <component :is="modalComp" :modal="modal"></component>
</template>
<script>
import { defineAsyncComponent } from 'vue'
import UploadModal from '@/components/modal/UploadModal.vue'

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
            } else if(this.modal.action == 'upload') {
                AsyncModal = UploadModal
            }

            return AsyncModal
        }
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/modal.scss';
</style>