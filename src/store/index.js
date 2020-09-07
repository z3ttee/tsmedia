import { createStore } from 'vuex'
import { version, changelog } from '../../package.json';
import config from '@/config.json'

const isDev = process.env.NODE_ENV === 'development'
const localStorageName = 'data'

if(isDev) {
    config.api.baseURL = "http://localhost/api/"
    config.api.url = "http://localhost/api/v1/"
} else {
    config.api.url = config.api.baseURL+config.api.version+"/"
}

const store = createStore({
    state() {
        return {
          toast: undefined,
          modal: undefined,
          user: {},
          uploads: {},
          metrics: {},
          config
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

                if(state.changelog != changelog) {
                    // TODO: Show changelog modal
                }
            }
        },
        updateUser(state, payload) {
            var user = {
                ...state.user,
                ...payload
            }
            state.user = user
        },
        updateUpload(state, payload) {
            var object = {}
            object[payload.id] = payload

            var updated = {
                ...state.uploads,
                ...object
            }

            state.uploads = updated
        },
        removeUpload(state, payload) {
            var uploads = state.uploads
            delete uploads[payload]

            state.uploads = uploads
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
        changelog: state.changelog || changelog,
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
