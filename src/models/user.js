import VueCookies from 'vue-cookies';
import axios from 'axios';
import store from '@/store';

const sessionCookieName = "tsr_session";

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
                
                VueCookies.set('ts_session', session.value, expiry, '/', null, null, true);
                this.loggedIn = true;

                store.state.user.access_token = access_token.value;

                callback({ok: true});
                this.loadInfo();
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
        var token = VueCookies.get(sessionCookieName) ?? undefined;
        if(token) {
            axios.get('user/').then(response => {
                console.log(response);
                /*if(response.status == 200 && response.data.meta.status == 200) {
                    var user = response.data.payload;
                    user.session = {token: token};
                    store.state.user = user
                } else {
                    this.logout();
                }*/
            });
        }
    }

    /*logout() {
        VueCookies.remove(sessionCookieName);
        this.loggedIn = false
        router.push({name: 'login'});
    }

    checkLogin(){
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