<template>
  <v-app>
    <v-app id="inspire">
      <v-app id="inspire">
        <v-navigation-drawer
          v-model="drawer"
          app
        >
          <v-list dense>
            <v-list-item link
                         v-for="(link, key) in navigation"
                         v-bind:key="key"
                         :to="link.link"
            >
              <v-list-item-action >
                <v-icon>{{ link.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>{{ link.title }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>

        <v-app-bar
          app
          color="blue"
          dark
        >
          <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
          <v-toolbar-title>{{ SUBJECT.title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          {{ USERNAME }}
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn
                v-on="on"
                @click="logout"
                primary
                icon>
                <v-icon>mdi-logout</v-icon>
              </v-btn>
            </template>
            <span>Выйти</span>
          </v-tooltip>
        </v-app-bar>

        <v-content>
          <v-container
            class="pa-0 mt-10"
          >
            <router-view/>
          </v-container>
        </v-content>

        <v-footer
          color="blue"
          app
        >
          <v-col
            class="text-center"
            cols="12"
          >
            <span class="white--text">&copy; timved@mail.ru , 2020</span>
          </v-col>
        </v-footer>
      </v-app>
    </v-app>
    <Notification />
  </v-app>
</template>

<script>
import Notification from '../components/Notification'
import { mapGetters } from 'vuex'

export default {
  name: 'AppLayout',
  components: {
    Notification
  },
  computed: {
    ...mapGetters(['USERNAME', 'FIO', 'SUBJECT', 'EMAIL'])
  },
  data () {
    return {
      drawer: null,
      navigation: [
        {
          title: 'Главная',
          link: '/',
          icon: 'mdi-home'
        },
        {
          title: 'Пользователь',
          link: '/account',
          icon: 'mdi-account'
        },
        {
          title: 'Заметки',
          link: '/notes',
          icon: 'mdi-book-open-page-variant'
        },
        {
          title: 'Пример',
          link: '/example',
          icon: 'mdi-alien-outline'
        }
      ]
    }
  },
  methods: {
    logout () {
      this.$store.dispatch('LOGOUT').then(() => {
        this.$router.push('/login')
      })
    }
  },
  mounted () {
    this.$store.dispatch('USER_INFO')
  }
}
</script>

<style scoped>

</style>
