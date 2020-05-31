<template>
  <div>
    Example page!!!!
    {{ EXAMPLE }}
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Example',
  computed: {
    ...mapGetters(['EXAMPLE'])
  },
  mounted () {
    this.$store.dispatch('GET_EXAMPLE')
  }
}
</script>

<style scoped>

</style>
