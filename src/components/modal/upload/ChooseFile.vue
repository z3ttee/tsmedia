<template>
    <div>
        <div class="dragdrop-wrapper" v-cloak @dragover.prevent @drop.prevent="addDroppedFile" @click="triggerFileDialog">
            <span>Dateien per Drag & Drop hinzufügen</span>
        </div>
        <div class="input-wrapper">
            <p v-if="error">{{ error }}</p>
            <label for="file_upload" class="btn btn-accent">Dateien manuell auswählen</label>
            <input type="file" name="file_upload" id="file_upload" accept="video/mpeg, video/mp4, video/ogg, video/webm, video/x-msvideo" multiple @change="addSelectedFile">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            error: undefined,
            selectedFile: undefined,
            mimeTypes: ['video/mpeg', 'video/mp4', 'video/ogg', 'video/webm', 'video/x-msvideo']
        }
    },
    methods: {
        triggerFileDialog(){
            document.getElementById('file_upload').click()
        },
        addDroppedFile(event) {
            var droppedFiles = event.dataTransfer.files
            if(!droppedFiles) return

            for(var file of droppedFiles) {
                this.addFile(file)
            }
        },
        addSelectedFile(event) {
            for(var file of event.target.files) {
                this.addFile(file)
            }
        },
        addFile(file) {
            console.log(file)
            this.error = undefined
            
            if(!this.mimeTypes.includes(file.type)) {
                this.error = 'Dateiformat nicht unterstützt: '+file.name
                document.getElementById('file_upload').value = ''
                return
            }

            //this.selectedFile = file
            this.$emit('file', file)
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.dragdrop-wrapper {
    position: relative;
    display: flex;
    height: 250px;
    border: 3px dashed $colorPlaceholder;
    justify-content: center;
    align-items: center;

    span {
        font-weight: 500;
        color: $colorPlaceholder;
    }
}
.input-wrapper {
    display: block;
    text-align: center;
    padding: $innerPad;

    p {
        padding-bottom: $innerPad/2;
        font-size: 0.8em;
        letter-spacing: 1px;
        color: $colorAccent;
    }

    input {
        display: none;
    }
}
</style>