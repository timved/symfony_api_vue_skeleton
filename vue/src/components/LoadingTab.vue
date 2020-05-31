<template>
  <v-container fluid>
    <v-overlay
      :absolute=false
      :opacity="0.65"
      :value="loading"
      class="fill-height"
    >
      <v-progress-circular
        :size="70"
        :width="7"
        color="primary"
        indeterminate
      ></v-progress-circular>
    </v-overlay>
    <template v-if="!loading">
      <slot></slot>
    </template>
  </v-container>
</template>

<script>
export default {
  name: 'LoadingTab',
  props: ['loading']
}
</script>

<style scoped>

</style>
