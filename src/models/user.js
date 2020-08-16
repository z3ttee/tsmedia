import VueCookies from 'vue-cookies';
import axios from 'axios';
import router from '@/router';
import store from '@/store';
import { toast } from '@/models/toast.js';
import Api from '@/models/api.js';

const sessionCookieName = "ts_session";

class User {

    loginWithCredentials(username, password, callback) {
        Api.get('auth/?name='+username+"&password="+password, {}, false).then((data) => {
            var access_token = data.access_token;
            var session = data.session_hash;
                
            this.setSession(session);
            this.setAccessToken(access_token);
            this.loggedIn = true;
            this.loadInfo();
            callback({ok: true});
        }).catch((error) => {
            callback({ok: false, error: error});
        })
    }

    login(callback) {
        if(!this.loggedIn) {
            var session = VueCookies.get(sessionCookieName) || undefined;

            if(session) {
                axios.get('auth/refresh/?session_hash='+session).then((response) => {

                    if(response.data.status.code == 200) {
                        var access_token = response.data.data.access_token;
                        var session = response.data.data.session_hash;

                        this.setAccessToken(access_token);
                        this.setSession(session);
                        this.loggedIn = true;
                        this.loadInfo();
                    } else {
                        this.showError({title: 'Ein Fehler ist aufgetreten',content: 'Deine Sitzung ist abgelaufen. Eine erneute Anmeldung ist erforderlich'});
                        this.logout();
                    }
                }).catch((error) => {
                    console.log(error);
                    this.logout();
                    this.showError({title: 'Ein Fehler ist aufgetreten',content: 'Deine Sitzung ist abgelaufen. Eine erneute Anmeldung ist erforderlich'});
                }).finally(() => {
                    callback(this.loggedIn);
                });
            } else {
                this.loggedIn = false;
                callback(this.loggedIn);
            }
        } else {
            callback(true);
            this.checkLogin();
        }
    }

    setSession(session) {
        var expiry = new Date(session.expiry).toString();
        VueCookies.set(sessionCookieName, session.value, expiry, '/', null, null, true);
    }
    setAccessToken(token) {
        store.state.user.access_token = token.value;
        axios.defaults.headers.common['Authorization'] = 'Bearer '+token.value;
    }

    loadInfo(){
        var session = VueCookies.get(sessionCookieName) ?? undefined;

        if(session) {
            axios.get('user/').then(response => {
                if(response.data.status.code == 200) {
                    var merged = {
                        ...response.data.data,
                        ...store.state.user
                    }

                    store.state.user = merged;
                } else {
                    this.showError({title: 'Ein Fehler ist aufgetreten',content: 'Dein Profil konnte nicht geladen werden. Bitte versuche es später erneut'});
                    this.logout();
                }
            }).catch((error) => {
                console.log(error);
                this.showError({title: 'Ein Fehler ist aufgetreten',content: 'Dein Profil konnte nicht geladen werden. Bitte versuche es später erneut'});
                this.logout();
            });
        }
    }

    logout() {
        var session = VueCookies.get(sessionCookieName) ?? undefined;

        if(session) {
            axios.get('auth/logout/?session_hash='+session).then((response) => {
                if(response.data.status.code != 200) {
                    this.showError({title: 'Ein Fehler ist aufgetreten',content: 'Deine Sitzung konnte nicht fehlerfrei geschlossen werden'});
                }
            }).finally(() => {
                VueCookies.remove(sessionCookieName);
                this.loggedIn = false
                store.state.user = {}
                axios.defaults.headers.common['Authorization'] = 'Bearer '+undefined;
    
                if(router.currentRoute.name != 'Home') {
                    router.push({name: 'Home'});         
                }
            });
        }
    }

    showError(data) {
        console.log('printing error');
        toast.error(data.content)
    }

    checkLogin(){
        if(this.loggedIn) {
            var session = VueCookies.get(sessionCookieName) ?? undefined;

            if(!session) {
                this.logout();
                return
            }

            axios.get('auth/refresh/?session_hash='+session).then((response) => {
                if(response.data.status.code != 200) {
                    this.logout();
                }
            }).catch((error) => {
                console.log(error);
                this.logout();
            });
        }
    }

    /*checkLogin(){
        var token = VueCookies.get(sessionCookieName) ?? undefined;
        if(token && this.checkSession()) {
            this.loggedIn = true;
        } else {
            this.logout();
        }
    }

    checkPermission() {

    }
    checkSession() {
        return true;
    }

    isLoggedIn() {
        this.checkLogin();
        return this.loggedIn;
    }*/
}

export default new User();