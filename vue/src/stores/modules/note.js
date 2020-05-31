import axios from 'axios'

export default {
  state: {
    notes: []
  },
  getters: {
    NOTES: state => {
      return state.notes
    }
  },
  mutations: {
    SET_NOTES: (state, payload) => {
      state.notes = payload
    }
  },
  actions: {
    GET_NOTES: async (context, params) => {
      console.log({ storeNotes: params })
      if (params === null) {
        let { notes } = await axios.get(`user/${context.getters.USER_ID}/notes`)
        context.commit('SET_NOTES', notes)
      } else {
        let { notes } = await axios.get(`user/${context.getters.USER_ID}/notes`, { params: params })
        context.commit('SET_NOTES', notes)
      }
    }
    // GET_NOTES: (data) => async (context) => {
    //   if (data === null) {
    //     let { notes } = await axios.get(`user/${context.getters.USER_ID}/notes`)
    //     context.commit('SET_NOTES', notes)
    //   } else {
    //     let { notes } = await axios.get(`user/${context.getters.USER_ID}/notes`, { params: data })
    //     context.commit('SET_NOTES', notes)
    //   }
    // }
  }
}
