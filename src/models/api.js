import axios from 'axios'
import user from '@/models/user.js'
import router from '@/router'
import Modal from '@/models/modal.js'
import Toast from '@/models/toast.js'
import store from '@/store'

class API {

    constructor() {
        axios.defaults.baseURL = store.state.config.api.url
    }

    get(url, config, printError = true) {
        return new Promise((resolve, reject) => {
            axios.get(url, config || {}).then(response => {
                if(response.data.status.code == 200) resolve(response.data.data);
                else this.handleResponse(response, reject, printError);
            }).catch(error => {
                this.handleError(error, printError);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }
    post(url, config, printError = true) {
        return new Promise((resolve, reject) => {
            axios.post(url, config.query || {}).then(response => {
                if(response.data.status.code == 200) resolve();
                else this.handleResponse(response, reject, printError);
            }).catch(error => {
                this.handleError(error, printError);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }
    put(url, config, printError = true) {
        return new Promise((resolve, reject) => {
            axios.put(url, config.query || {}).then(response => {
                if(response.data.status.code == 200) resolve();
                else this.handleResponse(response, reject, printError);
            }).catch(error => {
                this.handleError(error, printError);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }
    delete(url, config, printError = true) {
        return new Promise((resolve, reject) => {
            axios.delete(url, config || {}).then(response => {
                if(response.data.status.code == 200) resolve();
                else this.handleResponse(response, reject, printError);
            }).catch(error => {
                this.handleError(error, printError);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }
    upload(url, data, config, printError = true) {
        return new Promise((resolve, reject) => {
            config.headers = {
                'Content-Type': 'multipart/form-data'
            }

            var formData = new FormData()
            formData.append('file', data)

            axios.post(url, formData, config || {}).then(response => {
                if(response.data.status.code == 200) resolve(false)
                else this.handleResponse(response, reject, printError)
            }).catch(error => {
                if(axios.isCancel(error)) {
                    resolve(true)
                } else {
                    this.handleError(error, printError)
                    reject(error)
                }
            }).finally(() => {
                if(config && config.done) config.done()
            })
        })
    }

    handleResponse(response, reject, printError = true){
        if(response.data.status.code == 200) {
            return;
        }

        var message = response.data.status.message;

        if(!printError) {
            reject(message)
            return
        }

        if(message.startsWith('input invalid:')) {
            var paramName = message.replace('input invalid: [', '').replace(']', '');
            Toast.error('Bitte überprüfe die Eingabe für folgendes Feld: '+paramName);
        } else {
            switch(message) {
                case 'missing required params':
                    Toast.error('Bitte überprüfe deine Eingaben');
                    break;
                case 'no permission':
                    Toast.error('Keine Berechtigung');
                    user.checkLogin()
                    break;
                case 'no specific permission':
                    Toast.error('Dir fehlen Berechtigungen um einige der ausgewählten Elemente zu manipulieren');
                    user.checkLogin()
                    break;
                case 'invalid access token' || 'authentication required' || 'authorization header required' || 'session expired':
                    user.logout()
                    Modal.login(router.currentRoute.value)
                    break;
                case 'wrong credentials':
                    Toast.error('Benutzername stimmt nicht mit Passwort überein');
                    break;
                case 'not created' || 'not updated' || 'not deleted' || 'not uploaded':
                    Toast.error('Die Aktion wurde durch einen Fehler nicht beendet');
                    break;
                case 'name exists':
                    Toast.error('Dieser Name ist bereits vergeben');
                    break;
                case 'discordID exists':
                    Toast.error('Diese DiscordID ist bereits zugeordnet');
                    break;
                case 'not found':
                    //Toast.error('Es konnte nichts gefunden werden');
                    break;
                case 'unsupported encoding':
                    Toast.error('Das Format der Datei wird nicht akzeptiert');
                    break;
                case 'video exists':
                    Toast.error('Dieses Video wurde bereits hochgeladen');
                    break;
                case 'too large':
                    Toast.error('Die Datei ist zu groß! (Max: 8GB)');
                    break;
                case 'no resource':
                    Toast.error('Die Resource ist in der Datenbank vorhanden, aber die Datei kann nicht gefunden werden');
                    break;
                case 'higher tier required':
                    Toast.error('Du benötigst einen höheren Rang als der zu bearbeitende Benutzer');
                    break;

                default:
                    Toast.error('Der Service ist derzeit nicht verfügbar');
                    break;
            }
        }

        if(reject) reject(message);
    }

    handleError(error, printError = true) {
        console.log(error.message);
        if(!printError) return
        Toast.error('Der Service ist derzeit nicht verfügbar');
    }
}

export default new API();