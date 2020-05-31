import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home'
import Login from '../views/Auth/Login'
import Signup from '../views/Auth/Signup'
import store from '../stores/store'
import Example from '../views/Example'
import axios from 'axios'
import Account from '../views/Account'
import Note from '../views/Note'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'index',
    component: Home
  },
  {
    path: '/example',
    name: 'example',
    component: Example
  },
  {
    path: '/account',
    name: 'account',
    component: Account
  },
  {
    path: '/notes',
    name: 'notes',
    component: Note
  },
  {
    path: '/login',
    name: 'login',
    meta: {
      layout: 'simple-layout'
    },
    component: Login
  },
  {
    path: '/signup',
    name: 'signup',
    meta: {
      layout: 'simple-layout'
    },
    component: Signup
  }
]

const router = new VueRouter({
  mode: 'history',
  routes: routes,
  base: '/'
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && to.name !== 'signup' && !store.getters.isAuthenticated) next({ name: 'login' })
  else next()
})

let isRefreshing = false
let subscribers = []

axios.interceptors.response.use(
  response => {
    return response
  },
  err => {
    const {
      config,
      response: { status, data }
    } = err
    const originalRequest = config

    if (data.message === 'Missing token') {
      router.push({ name: 'login' })
      return Promise.reject(err)
    }

    if (originalRequest.url.includes('login_check')) {
      return Promise.reject(err)
    }

    if (status === 401 && data.message === 'Expired token') {
      if (!isRefreshing) {
        isRefreshing = true
        store
          .dispatch('REFRESH_TOKEN')
          .then(({ status }) => {
            if (status === 200 || status === 204) {
              isRefreshing = false
            }
            subscribers = []
          })
          .catch(error => {
            console.error(error)
          })
      }

      const requestSubscribers = new Promise(resolve => {
        subscribeTokenRefresh(() => {
          resolve(axios(originalRequest))
        })
      })

      onRefreshed()

      return requestSubscribers
    }
  }
)

function subscribeTokenRefresh (cb) {
  subscribers.push(cb)
}

function onRefreshed () {
  subscribers.map(cb => cb())
}

subscribers = []

export default router
