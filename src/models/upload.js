import store from '@/store'
import Api from '@/models/api.js'
import Toast from '@/models/toast.js'
import Axios from 'axios'
import UploadEventListener from '@/events/UploadEventListener.js'

class UploadManager {

    constructor() {
        UploadEventListener.on('ondone', () => {
            this.startNext()
        })
    }

    new(file, events = []) {
        var upload = new Upload(file, events)
        store.state.uploads[upload.id] = upload
        return upload
    }

    close(id) {
        console.log('TODO: Close upload '+id)
    }


    startNext() {
        var queue = this.getQueue()
        if(queue.length == 0) return

        store.state.uploads[queue[0]].start()
    }
    getQueue() {
        return Object.keys(store.state.uploads)
    }
    getRunning() {
        return Object.values(store.state.uploads).filter((element) => element.running == true)
    }
    canStart(){
        return this.getRunning().length == 0
    }

}

const manager = new UploadManager()

class Upload {

    constructor(file, events = []){
        this.id = 'id'+(new Date()).getTime()
        this.events = events
        this.name = file.name
        this.visibility = -1
        this.created = (new Date()).getTime()
        this.views = 0
        this.favs = 0
        this.progress = 0
        this.running = false
        this.error = undefined
        
        var cancelToken = Axios.CancelToken
        var source = cancelToken.source()

        this.cancel = () => {
            source.cancel('"'+this.name+'" cancelled by user')
        }
        
        var onUploadProgress = (event) => {
            var progress = Math.round((event.loaded / file.size)*100)
            this.progress = progress
            this.updateProgressReactively()
            UploadEventListener.emit('onprogress', this)
        }

        this.start = () => {
            this.running = true
            Api.upload('video/upload/', file, { cancelToken: source.token, onUploadProgress }, false).then((cancelled) => {
                if(cancelled) {
                    Toast.success('Upload von "'+this.name+'" wurde abgebrochen.')
                    UploadEventListener.emit('oncancel', this)
                } else {
                    UploadEventListener.emit('oncomplete', this)
                    Toast.success('"'+this.name+'" wurde hochgeladen.')
                }
            }).catch((error) => {
                UploadEventListener.emit('onerror', { upload: this, error })
                this.error = error
                Toast.error('"'+this.name+'" wurde nicht hochgeladen.')
            }).finally(() => {
                store.commit('removeUpload', this.id)
                UploadEventListener.emit('ondone')
            })
        }

        if(manager.canStart()) {
            this.start()
        }
    }

    updateProgressReactively() {
        store.commit('updateUpload', this)
    }
}

export default manager