import axios from 'axios'

export default {
  register (data) {
    return axios.post(`register`, data)
      .then(response => {
        return response
      })
  },
  refreshToken () {
    return axios.post(`token/refresh`)
      .then(response => {
        return response
      })
  },
  login (data) {
    return axios.post(`login_check`, data)
      .then(response => {
        return response
      })
  },
  logout (data) {
    return axios.post(`logout`, data)
      .then(response => {
        return response
      })
  },
  infoUser (data) {
    return axios.post(`user/info`, data)
      .then(response => {
        return response
      })
  },
  updateUser (userId, data) {
    return axios.post(`user/update/${userId}`, data)
      .then(response => {
        return response
      })
  }
}
