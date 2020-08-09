import axios from 'axios';
import Toast from '@/models/toast.js';

class API {

    get(url, config) {
        return new Promise((resolve, reject) => {
            axios.get(url, config || {}).then(response => {
                this.handleResponse(response, reject);
                if(response.data.status.code == 200) resolve(response.data.data);
            }).catch(error => {
                this.handleError(error);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }
    post(url, config) {
        return new Promise((resolve, reject) => {
            axios.post(url, config.query || {}).then(response => {
                this.handleResponse(response, reject);
                if(response.data.status.code == 200) resolve();
            }).catch(error => {
                this.handleError(error);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }
    put(url, config) {
        return new Promise((resolve, reject) => {
            axios.put(url, config.query || {}).then(response => {
                this.handleResponse(response, reject);
                if(response.data.status.code == 200) resolve();
            }).catch(error => {
                this.handleError(error);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }
    delete(url, config) {
        return new Promise((resolve, reject) => {
            axios.delete(url, config || {}).then(response => {
                this.handleResponse(response, reject);
                if(response.data.status.code == 200) resolve();
            }).catch(error => {
                this.handleError(error);
                reject(error);
            }).finally(() => {
                if(config && config.done) config.done();
            });
        });
    }

    handleResponse(response, reject){
        console.log(response);
        if(response.data.status.code == 200) {
            return;
        }

        var message = response.data.status.message;

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
                    break;
                case 'invalid access token' || 'authentication required' || 'authorization header required' || 'session expired':
                    // TODO: Show Login modal
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
                    Toast.error('Es konnte nichts gefunden werden');
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

                default:
                    Toast.error('Der Service ist derzeit nicht verfügbar');
                    console.log(message);
                    break;
            }
        }

        if(reject) reject(message);
    }

    handleError(error) {
        console.log(error.message);
        Toast.error('Der Service ist derzeit nicht verfügbar');
    }

}

export default new API();