export default {
  state: {
    notification: {
      display: false,
      text: 'Notification placeholder text',
      timeout: 3000,
      class: 'success'
    }
  },
  getters: {
    NOTIFICATION: state => {
      return state.notification
    }
  },
  mutations: {
    SET_NOTIFICATION: (state, { display, text, alertClass, timeout }) => {
      state.notification.display = display
      state.notification.text = text
      state.notification.class = alertClass
      state.notification.timeout = timeout
    }
  },
  actions: {}
}
