import axios from 'axios'

export default {
  state: {
    subjects: []
  },
  getters: {
    SUBJECTS: state => {
      return state.subjects
    }
  },
  mutations: {
    SET_SUBJECTS: (state, payload) => {
      state.subjects = payload
    }
  },
  actions: {
    GET_SUBJECTS: async ({ commit }) => {
      let { data } = await axios.get('public/dictionary/subjects')
      commit('SET_SUBJECTS', data)
    }
  }
}
