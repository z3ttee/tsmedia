const uploads = []


class UploadManager {

    new(events) {
        var upload = new Upload(events)
        uploads.push(upload)

        return upload
    }

    close(id) {
        console.log('TODO: Close upload '+id)
    }

}

class Upload {
    id = undefined
    events

    constructor(events){
        this.id = 'id'+(new Date()).getTime()
        this.events = events

        console.log(this)
    }
}

export default new UploadManager()