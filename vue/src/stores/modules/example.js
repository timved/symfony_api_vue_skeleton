import axios from 'axios'

export default {
  state: {
    example: []
  },
  getters: {
    EXAMPLE: state => {
      return state.example
    }
  },
  mutations: {
    SET_EXAMPLE: (state, payload) => {
      state.example = payload
    }
  },
  actions: {
    GET_EXAMPLE: async ({ commit }) => {
      let { data } = await axios.get('example')
      commit('SET_EXAMPLE', data)
    }
  }
}
