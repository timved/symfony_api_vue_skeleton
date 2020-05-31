import Vue from 'vue'
import Vuex from 'vuex'

import Example from './modules/example'
import UIModule from './modules/ui'
import User from './modules/user'
import DictionarySubjects from './modules/dictionaries/subject'
import Notes from './modules/note'

import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
  plugins: [createPersistedState({
    paths: ['user', 'dictionarySubjects']
  })],
  modules: {
    ui: UIModule,
    user: User,
    notes: Notes,
    dictionarySubjects: DictionarySubjects,
    example: Example
  }
})
