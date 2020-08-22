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
        updateUser(state, payload) {
            var user = {
                ...state.user,
                ...payload
            }
            state.user = user
        }
    },
    actions: {
    },
    modules: {
    },
    getters: {
        isLoggedIn() {
            return store.state.user.access_token != undefined
        },
        hasPermission: (state) => (permission) => {
            if(!state.user.permissions) return false
            return state.user.permissions.includes('*') || state.user.permissions.includes(permission)
        },
        getPermissions: (state) => {
            return state.user.permissions
        }
    }
})

store.subscribe((mutation, state) => {
    const store = {
        version: state.version || version,
        user: {
            id: state.user.id,
            name: state.user.name,
            permissionGroup: state.user.permissionGroup,
            permissions: state.user.permissions,
            hierarchy: state.user.hierarchy
        }
    }

    localStorage.setItem(localStorageName, JSON.stringify(store));
})

export default store
