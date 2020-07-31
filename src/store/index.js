import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
      routeLoading: false,
      user: {},
      activeModals: [],
      notice: {
          current: undefined,
          queue: []
      }
  },
  mutations: {
  },
  actions: {
  },
  modules: {
  }
})
