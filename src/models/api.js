import axios from 'axios';
import user from '@/models/user.js'
import router from '@/router'
import { modal } from '@/models/modal.js'
import { toast } from '@/models/toast.js';

class API {

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

    handleResponse(response, reject, printError = true){
        //console.log(response);
        if(response.data.status.code == 200) {
            return;
        }

        var message = response.data.status.message;

        console.log('print error: '+printError)
        if(!printError) {
            reject(message)
            return
        }

        console.log(message)

        if(message.startsWith('input invalid:')) {
            var paramName = message.replace('input invalid: [', '').replace(']', '');
            toast.error('Bitte überprüfe die Eingabe für folgendes Feld: '+paramName);
        } else {
            switch(message) {
                case 'missing required params':
                    toast.error('Bitte überprüfe deine Eingaben');
                    break;
                case 'no permission':
                    toast.error('Keine Berechtigung');
                    break;
                case 'invalid access token' || 'authentication required' || 'authorization header required' || 'session expired':
                    // TODO: Show Login modal
                    user.logout()
                    modal.login(router.currentRoute.value)
                    break;
                case 'wrong credentials':
                    toast.error('Benutzername stimmt nicht mit Passwort überein');
                    break;
                case 'not created' || 'not updated' || 'not deleted' || 'not uploaded':
                    toast.error('Die Aktion wurde durch einen Fehler nicht beendet');
                    break;
                case 'name exists':
                    toast.error('Dieser Name ist bereits vergeben');
                    break;
                case 'discordID exists':
                    toast.error('Diese DiscordID ist bereits zugeordnet');
                    break;
                case 'not found':
                    toast.error('Es konnte nichts gefunden werden');
                    break;
                case 'unsupported encoding':
                    toast.error('Das Format der Datei wird nicht akzeptiert');
                    break;
                case 'video exists':
                    toast.error('Dieses Video wurde bereits hochgeladen');
                    break;
                case 'too large':
                    toast.error('Die Datei ist zu groß! (Max: 8GB)');
                    break;
                case 'no resource':
                    toast.error('Die Resource ist in der Datenbank vorhanden, aber die Datei kann nicht gefunden werden');
                    break;

                default:
                    toast.error('Der Service ist derzeit nicht verfügbar');
                    console.log(message);
                    break;
            }
        }

        if(reject) reject(message);
    }

    handleError(error, printError = true) {
        console.log(error.message);
        if(!printError) return
        toast.error('Der Service ist derzeit nicht verfügbar');
    }
}

export default new API();