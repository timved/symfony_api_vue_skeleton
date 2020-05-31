<template>
  <v-app>
    <component v-bind:is="layout"></component>
  </v-app>
</template>

<script>

import AppLayout from './layouts/AppLayout'
import SimpleLayout from './layouts/SimpleLayout'

export default {
  name: 'App',
  components: {
    'app-layout': AppLayout,
    'simple-layout': SimpleLayout
  },
  computed: {
    layout () {
      return (this.$route.meta.layout || 'app-layout')
    }
  }
}
</script>

<style>

</style>
