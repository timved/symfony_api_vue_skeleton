<template>
  <div>
    <v-flex>
      <v-card class="elevation-3 mb-3">
        <v-toolbar>
          <v-toolbar-title>
            Новая заметка
          </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form>

            <v-text-field
              outlined
              v-model="title"
              prepend-icon="mdi-account-circle"
              name="note-title"
              label="Заголовок"
              :rules="[rules.required]"
            >
            </v-text-field>

            <v-textarea
              outlined
              name="note-title"
              v-model="text"
              label="Текст"
              :rules="[rules.required]"
            ></v-textarea>
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
            Добавить
            <v-icon>mdi-keyboard_arrow_up</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
    <v-flex>
      <loading-tab :loading="loading">
        <paginator-table
          :headers="headers"
          :pagination="NOTES"
          @edit-item="editItem"
          @update="getNotes"
        />
      </loading-tab>
    </v-flex>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import PaginatorTable from '../components/tables/PaginatorTable'
import LoadingTab from '../components/LoadingTab'

export default {
  name: 'Note',
  components: {
    PaginatorTable,
    LoadingTab
  },
  data () {
    return {
      title: null,
      text: null,
      rules: {
        required: value => !!value || 'Необходимо заполнить'
      },
      pagination: {},
      headers: [
        { text: 'Заголовок', value: 'title', filter: 'n.title', sortable: true, template: 'item' },
        { text: 'Текст', value: 'text', filter: 'n.text', sortable: true, template: 'item' },
        { text: 'Дата создания', value: 'created', filter: 'n.created', sortable: true, template: 'date' },
        { text: 'Дата обновление', value: 'updated', filter: 'n.updated', sortable: true, template: 'date' },
        { template: 'edit' }
      ],
      loading: true
    }
  },
  computed: {
    ...mapGetters(['NOTES'])
  },
  methods: {
    editItem (item) {
      console.log(item)
    },
    getNotes (data = null) {
      this.$store.dispatch('GET_NOTES', { params: data })
      // this.pagination = this.$store.getters.NOTES
      console.log(this.pagination)
      this.loading = false
    },
    success () {
      console.log(this.title)
    }
  },
  mounted () {
    this.getNotes()
    // let ooo = this.$store.dispatch('GET_NOTES')
    // console.log(ooo)
  }
}
</script>

<style scoped>

</style>
