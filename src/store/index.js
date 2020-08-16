import { createStore } from 'vuex'
import { version } from '../../package.json';

const localStorageName = 'data'

const store = createStore({
    state() {
        return {
          toast: undefined,
          modal: undefined,
          user: {}
        }
    },
    mutations: {
        initialiseStore(state){
            if(localStorage.getItem(localStorageName)) {
                const store = JSON.parse(localStorage.getItem(localStorageName))

                if(store.version == version) {
                    this.replaceState(Object.assign(state, JSON.parse(localStorage.getItem(localStorageName))))
                } else {
                    state.version = version
                }
            }
        },
        updateUser() {}
    },
    actions: {
    },
    modules: {
    }
})

store.subscribe((mutation, state) => {
    const store = {
        version: state.version || version,
        user: {
            id: state.user.id,
            name: state.user.name,
            permissionGroup: state.user.permissionGroup,
            access_token: state.user.access_token
        }
    }

    localStorage.setItem(localStorageName, JSON.stringify(store));
})

export default store
