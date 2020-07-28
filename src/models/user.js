import VueCookies from 'vue-cookies';
import axios from 'axios';
import router from '@/router';
import store from '@/store';

const sessionCookieName = "ts_session";

class User {

    constructor() {
        //axios.defaults.baseURL = 'http://localhost/api/v1/';
        //axios.defaults.headers.common['Authorization'] = 'Bearer '+store.state.user.access_token;

        /*this.checkLogin();
        if(this.isLoggedIn()) {
            this.loadInfo();
        }*/
    }

    loginWithCredentials(username, password, callback) {
        axios.get('auth/?name='+username+"&password="+password).then(response => {
            if(response.data.status.code == 200) {
                var access_token = response.data.data.access_token;
                var session = response.data.data.session_hash;
                var expiry = new Date(session.expiry).toString();
                
                VueCookies.set(sessionCookieName, session.value, expiry, '/', null, null, true);
                this.loggedIn = true;

                store.state.user.access_token = access_token.value;

                axios.defaults.headers.common['Authorization'] = 'Bearer '+store.state.user.access_token;
                
                this.loadInfo();
                callback({ok: true});
            } else {
                this.loggedIn = false
                callback({ok: false, message: response.data.status.message});
            }
        }).catch(error => {
            console.log(error);
            this.loggedIn = false
            callback({ok: false, message: error});
        });
    }

    loadInfo(){
        var session = VueCookies.get(sessionCookieName) ?? undefined;

        if(session) {
            axios.get('user/').then(response => {
                if(response.data.status.code == 400) {
                    var merged = {
                        ...response.data.data,
                        ...store.state.user
                    }

                    store.state.user = merged;
                } else {
                    var modal = {
                        id: 'id'+(new Date()).getTime(),
                        data: {
                            title: 'Ein Fehler ist aufgetreten',
                            content: 'Dein Profil konnte nicht geladen werden. Bitte versuche es später erneut'
                        },
                        buttons: {
                            positive: {
                                text: 'OK'
                            }
                        },
                        component: () => import('@/components/modal/InfoModal.vue')
                    }
            
                    store.state.activeModals.push(modal);
                    this.logout();
                }
            }).catch((error) => {
                console.log(error);
                var modal = {
                    id: 'id'+(new Date()).getTime(),
                    data: {
                        title: 'Ein Fehler ist aufgetreten',
                        content: 'Dein Profil konnte nicht geladen werden. Bitte versuche es später erneut'
                    },
                    buttons: {
                        positive: {
                            text: 'OK'
                        }
                    },
                    component: () => import('@/components/modal/InfoModal.vue')
                }
        
                store.state.activeModals.push(modal);
                this.logout();
            });
        }
    }

    logout() {
        VueCookies.remove(sessionCookieName);
        this.loggedIn = false
        store.state.user = {}
        axios.defaults.headers.common['Authorization'] = 'Bearer '+undefined;

        if(router.currentRoute.name != 'Home') {
            router.push({name: 'Home'});         
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