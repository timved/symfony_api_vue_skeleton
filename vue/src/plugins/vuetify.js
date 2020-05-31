import '@mdi/font/css/materialdesignicons.css'
import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import colors from 'vuetify/lib/util/colors'

Vue.use(Vuetify)

export default new Vuetify({
  theme: {
    dark: false,
    themes: {
      light: {
        primary: colors.blue.darken1,
        secondary: colors.red.lighten4,
        accent: colors.indigo.base
      },
      dark: {
        primary: colors.red.darken1,
        secondary: colors.red.lighten4,
        accent: colors.indigo.base
      }
    }
  },
  icons: {
    iconfont: 'mdiSvg'
  }
})
