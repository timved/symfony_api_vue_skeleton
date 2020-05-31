import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import axios from 'axios'
import store from './stores/store'

Vue.config.productionTip = false

axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URI
axios.defaults.withCredentials = true

new Vue({
  router,
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
