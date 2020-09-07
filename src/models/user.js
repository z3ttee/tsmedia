import VueCookies from 'vue-cookies';
import axios from 'axios';
import router from '@/router';
import store from '@/store';
import Toast from '@/models/toast.js';
import Api from '@/models/api.js';

const sessionCookieName = "ts_session";

class User {

    loginWithCredentials(username, password, callback) {
        Api.get('auth/?name='+username+"&password="+password, {}, false).then((data) => {
            var access_token = data.access_token.value;
            var session = data.session_hash;
                
            this.setSession(session);
            this.setAccessToken(access_token);

            this.loadInfo();
            callback({ok: true});
        }).catch((error) => {
            callback({ok: false, error: error});
        })
    }

    setSession(session) {
        var expiry = new Date(session.expiry).toString();
        VueCookies.set(sessionCookieName, session.value, expiry, '/', null, null, true);
    }
    setAccessToken(token) {
        store.commit('updateUser', {access_token: token})
        axios.defaults.headers.common['Authorization'] = 'Bearer '+token;
    }

    loadInfo(){
        var session = VueCookies.get(sessionCookieName) ?? undefined;

        if(session) {
            Api.get('user/', {}, false).then((data) => {
                store.commit('updateUser', data)
                console.log("Profile loaded")
            }).catch((error) => {
                console.log(error)
                if(error == 'not found') {
                    Toast.error('Dein Profil konnte nicht geladen, da es nicht existiert')
                    this.logout()
                } else {
                    Toast.error('Deine Sitzung ist abgelaufen')
                }
            })
        }
    }

    logout() {
        var session = VueCookies.get(sessionCookieName) ?? undefined;
        this.clear()

        if(router.currentRoute.name != 'home') {
            router.push({name: 'home'});         
        }

        if(session) {
            Api.get('auth/logout/?session_hash='+session, {}, false).then(() => {
                Toast.success('Du wurdest erfolgreich abgemeldet')
            }).catch(() => {
                Toast.error('Ein Fehler ist aufgetreten während du abgemeldet wurdest')
            })
        }
    }

    clear() {
        store.commit('updateUser', {});
        localStorage.removeItem('data')
        VueCookies.remove(sessionCookieName)
    }

    showError(data) {
        Toast.error(data.content)
    }

    checkLogin(){
        return new Promise((resolve, reject) => {
            var session = VueCookies.get(sessionCookieName) ?? undefined;

            if(session) {
                Api.get('auth/refresh/?session_hash='+session, {}, false).then((data) => {
                    this.setAccessToken(data.access_token.value)
                    this.setSession(data.session_hash)
                    resolve()
                    this.loadInfo()
                    console.log('Session refreshed')
                }).catch((error) => {
                    console.log(error)
                    if(error == 'session expired') {
                        Toast.error('Deine Sitzung ist abgelaufen')
                        this.logout()
                    }
                    Toast.error('Deine Sitzung kann nicht aktualisiert werden.')
                    reject()
                });
            } else {
                console.info('Profile not loaded: User not logged in')
                this.clear()
                reject()
            }
        })
    }

    hasPermission(permission) {
        if(!store.getters.isLoggedIn) return false
    
        var permissions = store.state.user.permissions || []
        if(permissions.includes('*')) return true

        return permissions.includes(permission)
    }

    isLoggedIn() {
        return store.getters.isLoggedIn || false
    }
}

export default new User();