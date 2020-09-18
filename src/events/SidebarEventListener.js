import mitt from 'mitt'
import store from '@/store'

const eventbus = mitt()

eventbus.on('toggle', (show) => {
    store.state.sidebarHidden = show || !store.state.sidebarHidden
}) 

export default eventbus