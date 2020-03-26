// import Vue from 'vue'
// import Vuex from 'vuex'
//
// Vue.use(Vuex)
//
// export default new Vuex.Store({
//   state: {
//   },
//   mutations: {
//   },
//   actions: {
//   },
//   modules: {
//   }
// })

import Vue from 'vue'
import Vuex from 'vuex'
import cliente from '../store/cliente/index';

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    cliente
  },
  debug: false,
})
