import UserApi from '../../services/UserApi'

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
    LOGIN: (context, payload) => {
      return new Promise((resolve, reject) => {
        UserApi.login(payload)
          .then(response => {
            if (response.status === 200 || response.status === 204) {
              context.commit('SET_EMAIL', payload.email)
              resolve(true)
            }
          }).catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    USER_INFO: (context) => {
      return new Promise((resolve, reject) => {
        UserApi.infoUser(context.state)
          .then(response => {
            if (response.status === 200) {
              context.commit('SET_USER_ID', response.data.id)
              context.commit('SET_USERNAME', response.data.login)
              context.commit('SET_FIO', response.data.fio)
              context.commit('SET_SUBJECT', response.data.subject)
              resolve(true)
            }
          }).catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    UPDATE_USER: (context, { login, fio, subject, password }) => {
      return new Promise((resolve, reject) => {
        UserApi.updateUser(context.getters.USER_ID, { login, fio, subject, password })
          .then(response => {
            if (response.status === 200) {
              context.commit('SET_USERNAME', response.data.login)
              context.commit('SET_FIO', response.data.fio)
              context.commit('SET_SUBJECT', response.data.subject)
              resolve(true)
            }
          }).catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    LOGOUT: (context) => {
      return new Promise((resolve, reject) => {
        UserApi.logout(context.state)
          .then(response => {
            if (response.status === 200) {
              context.commit('logout')
              resolve(true)
            }
          }).catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    REGISTER: (context, { login, email, fio, subject }) => {
      return new Promise((resolve, reject) => {
        UserApi.register({ login, email, fio, subject })
          .then(response => {
            if (response.status === 201) {
              resolve(true)
            }
          }).catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    REFRESH_TOKEN: () => {
      return new Promise((resolve, reject) => {
        UserApi.refreshToken()
          .then(response => {
            resolve(response)
          }).catch(error => {
            console.log(error)
            reject(error)
          })
      })
    }
  }
}
