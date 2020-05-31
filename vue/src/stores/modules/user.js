import axios from 'axios'

export default {
  state: {
    id: null,
    email: null,
    username: null,
    subject: null,
    fio: null
  },
  getters: {
    USER_ID: state => {
      return state.id
    },
    EMAIL: state => {
      return state.email
    },
    USERNAME: state => {
      return state.username
    },
    SUBJECT: state => {
      return state.subject
    },
    FIO: state => {
      return state.fio
    },
    isAuthenticated: state => !!state.email
  },
  mutations: {
    SET_USER_ID: (state, payload) => {
      state.id = payload
    },
    SET_EMAIL: (state, payload) => {
      state.email = payload
    },
    SET_USERNAME: (state, payload) => {
      state.username = payload
    },
    SET_SUBJECT: (state, payload) => {
      state.subject = payload
    },
    SET_FIO: (state, payload) => {
      state.fio = payload
    },
    logout: (state) => {
      state.email = null
    }
  },
  actions: {
    LOGIN: ({ commit }, payload) => {
      return new Promise((resolve, reject) => {
        axios.post(`login_check`, payload)
          .then(({ data, status }) => {
            console.log(data)
            console.log(status)
            if (status === 200 || status === 204) {
              commit('SET_EMAIL', payload.email)
              resolve(true)
            }
          })
          .catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    USER_INFO: (context) => {
      return new Promise((resolve, reject) => {
        axios.post(`user/info`, context.state)
          .then(({ data, status }) => {
            if (status === 200) {
              context.commit('SET_USER_ID', data.id)
              context.commit('SET_USERNAME', data.login)
              context.commit('SET_FIO', data.fio)
              context.commit('SET_SUBJECT', data.subject)
              resolve(true)
            }
          })
          .catch(error => {
            reject(error)
          })
      })
    },
    UPDATE_USER: (context, { login, fio, subject, password }) => {
      return new Promise((resolve, reject) => {
        axios.post(`user/update/${context.getters.USER_ID}`, { login, fio, subject, password })
          .then(({ data, status }) => {
            if (status === 200) {
              context.commit('SET_USERNAME', data.login)
              context.commit('SET_FIO', data.fio)
              context.commit('SET_SUBJECT', data.subject)
              resolve(true)
            }
          })
          .catch(error => {
            reject(error)
          })
      })
    },
    LOGOUT (context) {
      return new Promise((resolve, reject) => {
        axios.post(`logout`, context.state)
          .then(({ status }) => {
            if (status === 200) {
              context.commit('logout')
              resolve(true)
            }
          })
          .catch(error => {
            reject(error)
          })
      })
    },
    REGISTER: ({ commit }, { login, email, fio, subject }) => {
      return new Promise((resolve, reject) => {
        axios.post(`register`, {
          login, email, fio, subject
        })
          .then(({ data, status }) => {
            if (status === 201) {
              resolve(true)
            }
          })
          .catch(error => {
            reject(error)
          })
      })
    },
    REFRESH_TOKEN: () => {
      return new Promise((resolve, reject) => {
        axios.post(`token/refresh`)
          .then(response => {
            resolve(response)
          })
          .catch(error => {
            reject(error)
          })
      })
    }
  }
}
