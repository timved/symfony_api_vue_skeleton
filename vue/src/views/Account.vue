<template>
  <v-container fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md6>
        <v-card class="elevation-0">
          <v-toolbar>
            <v-toolbar-title>
              {{ $store.getters.EMAIL }}
            </v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form>

              <v-text-field
                v-model="login"
                prepend-icon="mdi-account-circle"
                name="login"
                label="Логин"
                :rules="[rules.required]"
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
                :items="$store.getters.SUBJECTS"
                item-text="title"
                item-value="code"
                label="Регион"
                :rules="[rules.required]"
                single-line
              ></v-select>

              <v-text-field
                prepend-icon="mdi-lock"
                name="password"
                label="Пароль"
                type="password"
                v-model="password"
              >

              </v-text-field>
              <v-text-field
                prepend-icon="mdi-lock"
                name="password"
                label="Повторите пароль"
                type="password"
                :error="!valid()"
                v-model="confirmPassword"
              >
              </v-text-field>

            </v-form>
          </v-card-text>
          <v-divider light></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="success"
              rounded
              @click.prevent="success"
            >
              Изменить
              <v-icon>mdi-keyboard_arrow_up</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'Account',
  data: () => ({
    login: null,
    fio: null,
    subject: null,
    password: null,
    confirmPassword: null,
    rules: {
      required: value => !!value || 'Необходимо заполнить'
    }
  }),
  methods: {
    valid () {
      return this.password === this.confirmPassword
    },
    success () {
      this.$store.dispatch('UPDATE_USER', {
        login: this.login,
        fio: this.fio,
        subject: this.subject,
        password: this.password
      })
        .then(({ status }) => {
          this.$store.commit('SET_NOTIFICATION', {
            display: true,
            text: 'Данные изменены',
            alertClass: 'success',
            timeout: 10000
          })
        })
        .catch(error => {
          console.log(error)
        })
    }
  },
  mounted () {
    this.$store.dispatch('GET_SUBJECTS')
    this.login = this.$store.getters.USERNAME
    this.fio = this.$store.getters.FIO
    this.subject = this.$store.getters.SUBJECT.code
  }
}
</script>

<style scoped>

</style>
