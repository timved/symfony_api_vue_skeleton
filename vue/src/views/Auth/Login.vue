<template>
  <v-container fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md6>
        <v-form>
          <v-card class="elevation-12">
            <v-toolbar dark color="blue">
              <v-toolbar-title>
                Форма входа
              </v-toolbar-title>
            </v-toolbar>
            <v-alert
              color="error"
              :value="error"
              icon="mdi-alert-octagram"
            >
              Неверный e-mail или пароль.
            </v-alert>
            <v-card-text>
              <v-text-field
                v-model="email"
                prepend-icon="mdi-email"
                name="login"
                label="E-mail"
                type="text"
              >
              </v-text-field>
              <v-text-field
                v-model="password"
                prepend-icon="mdi-lock"
                name="password"
                label="Пароль"
                type="password"
              >
              </v-text-field>
            </v-card-text>
            <v-divider light></v-divider>
            <v-card-actions>
              <v-btn
                to="/signup"
                rounded
                color="indigo"
                dark
              >Регистрация
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn
                rounded
                color="primary"
                dark
                @click.prevent="login"
              >
                Войти
                <v-icon>mdi-keyboard_arrow_right</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-form>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'Login',
  data: () => ({
    email: '',
    password: '',
    error: false
  }),
  methods: {
    login () {
      this.$store.dispatch('LOGIN', {
        email: this.email,
        password: this.password
      })
        .then(success => {
          this.$router.push('/')
        })
        .catch(error => {
          this.error = true
          console.log(error)
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
