<template>
  <v-container fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md6>
        <v-card class="elevation-12">
          <v-toolbar dark color="blue">
            <v-toolbar-title>
              Форма регистрации
            </v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form>
              <v-alert
                :value="userExists"
                color="error"
                icon="mdi-alert-octagram"
              >
                Пользователь с таким e-mail уже существует.
              </v-alert>

              <v-text-field
                v-model="login"
                prepend-icon="mdi-account-circle"
                name="login"
                label="Логин"
                :rules="[rules.required]"
              >
              </v-text-field>

              <v-text-field
                v-model="email"
                prepend-icon="mdi-email"
                name="email"
                label="E-mail"
                :rules="[rules.required, rules.email]"
              >
              </v-text-field>

              <v-text-field
                v-model="fio"
                prepend-icon="mdi-account"
                name="fio"
                label="Ф.И.О."
                :rules="[rules.required]"
              >

              </v-text-field>
              <v-select
                prepend-icon="mdi-map"
                v-model="subject"
                :items="SUBJECTS"
                item-text="title"
                item-value="code"
                label="Регион"
                :rules="[rules.required]"
                single-line
              ></v-select>
            </v-form>
          </v-card-text>
          <v-divider light></v-divider>
          <v-card-actions>
            <v-btn to="/login" rounded dark color="black">
              Войти
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="success"
              rounded
              @click.prevent="register"
            >
              Зарегестрировать
              <v-icon>mdi-keyboard_arrow_up</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Signup',
  data: () => ({
    userExists: false,
    login: '',
    email: '',
    fio: '',
    subject: '',
    rules: {
      required: value => !!value || 'Необходимо заполнить',
      email: value => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        return pattern.test(value) || 'Неверный e-mail.'
      }
    }
  }),
  computed: {
    ...mapGetters(['SUBJECTS'])
  },
  mounted () {
    this.$store.dispatch('GET_SUBJECTS')
  },
  methods: {
    register () {
      this.$store.dispatch('REGISTER', {
        login: this.login,
        email: this.email,
        fio: this.fio,
        subject: this.subject
      })
        .then(({ status }) => {
          this.$store.commit('SET_NOTIFICATION', {
            display: true,
            text: 'Вы успешно зарегестрировались! На вашу почту выслан пароль для входа.',
            alertClass: 'success',
            timeout: 10000
          })
          this.$router.push('/login')
        })
        .catch(error => {
          console.log(error)
          this.userExists = true
        })
    }
  },
  created () {
    if (this.$store.getters.isAuthenticated) {
      this.$router.push('/')
    }
  }
}
</script>

<style scoped>

</style>
