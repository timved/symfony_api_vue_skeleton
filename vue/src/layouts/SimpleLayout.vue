<template>
  <div>
    <router-view></router-view>
    <Notification />
  </div>
</template>

<script>
import Notification from '../components/Notification'

export default {
  name: 'SimpleLayout',
  components: {
    Notification
  }
}
</script>

<style scoped>

</style>
