<template>
    <div class="modal dark">
        <div class="modal-wrapper">
            <div class="modal-header">
                <p>Videos hochladen</p>
                <button class="btn btn-tertiary btn-align-center" @click="$modal.dismiss()"><img src="@/assets/images/icons/close.svg"></button>
            </div>
            <div class="modal-content">
                <div class="modal-steps-wrapper">
                    <p class="error-box" v-if="error">{{ error }}</p>
                    <component :is="stepComponent" @file="initUpload"></component>
                </div>
            </div>
            <div class="modal-footer">
                <p v-if="!uploadID">Wähle eine Datei um fortzufahren</p>
                <div class="upload-progress" v-else>
                    <app-progress-bar class="progress-col" :progress="progress">{{ progress == 100 ? 'Video hochgeladen' : 'wird hochgeladen...' }}</app-progress-bar>
                    <div class="progress-col">
                        <app-button class="btn btn-light" style="float: rigth;">Speichern</app-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppProgressBar from '@/components/progress/AppProgressBar.vue'
import DragDropFile from '@/components/modal/upload/ChooseFile.vue'
import FileDetails from '@/components/modal/upload/FileDetails.vue'

import UploadManager from '@/models/upload.js'

export default {
    setup() {
        return { calcModalContent }
    },
    components: {
        DragDropFile,
        AppProgressBar
    },
    props: {
        modal: Object
    },
    data() {
        return {
            uploadID: undefined,
            setupStep: 1,
            progress: 0,
            error: undefined
        }
    },
    methods: {
        initUpload(file) {
            var upload = UploadManager.new()
            
            this.uploadID = upload.id
            this.setupStep++

            var onUploadProgress = (event) => {
                var progress = Math.round((event.loaded / file.size)*100)
                this.progress = progress
            }

            this.$api.upload('video/upload/', file, { onUploadProgress }, false).catch((error) => {
                if(error == 'video exists') {
                    this.setupStep = 1
                    this.error = 'Bitte wähle ein anderes Video, dieses existiert bereits'
                } else {
                    this.$toast.error('Das Video konnte nicht hochgeladen werden')
                    this.setupStep = 1
                }

                this.uploadID = undefined
                this.progress = 0
                UploadManager.close(this.uploadID)
            }).finally(() => {
                console.log('Upload done')
            })
        }
    },
    computed: {
        stepComponent() {
            switch(this.setupStep) {
                case 2:
                    return FileDetails
                default: 
                    return DragDropFile
            }
        }
    },
    mounted() {
        this.calcModalContent()

        window.onresize = () => {
            this.calcModalContent()
        }
    }
}

function calcModalContent() {
    var footerHeight = document.getElementsByClassName('modal-footer')[0].getBoundingClientRect().height
    var modal = document.getElementsByClassName('modal')[0]
    var modalInfo = modal.getClientRects()[0]
    var contentHeight = modalInfo.height-footerHeight
    document.getElementsByClassName('modal-content')[0].style.height = contentHeight+'px'
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.modal {
    width: 90%;
    height: 90%;
}
.modal-wrapper {
    height: 100%;
}

#video-preview {
    width: 33%;
}

.progress-col {
    display: inline-block;
    width: 50%;
    vertical-align: middle;

    &:last-of-type {
        text-align: right;
    }
}
</style>