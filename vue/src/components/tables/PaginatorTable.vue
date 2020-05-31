<template>
<div>
  <v-card flat class="text-right pr-5 mb-5">

    <v-tooltip top>
      <template v-slot:activator="{ on }">
        <v-btn small fab color="success" v-on="on" @click="acceptFilter">
          <v-icon
          >
            mdi-check
          </v-icon>
        </v-btn>
      </template>
      <span>Применить фильтры</span>
    </v-tooltip>
    <v-divider vertical class="mx-2"></v-divider>
    <v-tooltip bottom>
      <template v-slot:activator="{ on }">
        <v-btn small fab color="grey" v-on="on" @click="clearFilter">
          <v-icon
          >
            mdi-close
          </v-icon>
        </v-btn>
      </template>
      <span>Очистить фильтры</span>
    </v-tooltip>

  </v-card>
  <v-simple-table class="pl-5 pr-5">
      <thead>
      <tr>
        <th
          v-for="(header, key) in headers"
          v-bind:key="key"
        >

          <v-text-field
            class="d-flex"
            v-model="params[header.filter]"
            clearable
            autocomplete="off"
            :label="header.text"
            v-if="header.filter && pagination.filters[header.filter] === 'like'"
          >
            <template v-slot:append-outer>
              <v-btn
                class="d-flex"
                v-if="header.sortable === true && header.template !== 'html'"
                small
                color="primary"
                @click="sortItems(header.filter)"
                icon>
                <v-icon small>mdi-sort</v-icon>
              </v-btn>
            </template>
          </v-text-field>

          <v-menu
            v-if="header.filter && pagination.filters[header.filter] === 'date'"
            v-model="datepickers['menu' + key]"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                :label="header.text"
                v-model="params[header.filter]"
                @click:clear="params[header.filter] = ''"
                persistent-hint
                readonly
                clearable
                autocomplete="off"
                v-on="on"
              >
                <template v-slot:append-outer>
                  <v-btn
                    class="d-flex"
                    v-if="header.sortable === true && header.template !== 'html'"
                    small
                    color="primary"
                    @click="sortItems(header.filter)"
                    icon>
                    <v-icon small>mdi-sort</v-icon>
                  </v-btn>
                </template>
              </v-text-field>
            </template>
            <v-date-picker
              v-model="date"
              @input="datepickerAccept(date, header.filter, key)"
              locale="ru-ru"
              no-title
              scrollable
            >
            </v-date-picker>
          </v-menu>

          <v-select
            :items="pagination.filters[header.filter].options"
            :label="header.text"
            item-text="title"
            item-value="id"
            @click:clear="params[header.filter] = ''"
            clearable
            hide-selected
            v-model="params[header.filter]"
            v-if="header.filter && pagination.filters[header.filter].type === 'eq'"
          >
            <template v-slot:append-outer>
              <v-btn
                class="d-flex"
                v-if="header.sortable === true && header.template !== 'html'"
                small
                color="primary"
                @click="sortItems(header.filter)"
                icon>
                <v-icon small>mdi-sort</v-icon>
              </v-btn>
            </template>
          </v-select>

          <v-text-field
            v-if="!header.filter && (header.template === 'item' || header.template === 'date' || header.template === 'datetime')"
            class="d-flex"
            autocomplete="off"
            disabled
            :label="header.text"
          >
          </v-text-field>

          <v-text-field
            class="d-flex"
            disabled
            :label="header.text"
            v-if="header.template === 'html'"
          >
          </v-text-field>

        </th>
      </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, key) in pagination.data"
          v-bind:key="key"
        >
          <td v-for="(value, index) in headers" v-bind:key="index">
            <span v-if="value.template === 'date'">{{ item[value.value] ? formatDate(item[value.value]) : '' }}</span>
            <span v-if="value.template === 'datetime'">{{ item[value.value] ? formatDateTime(item[value.value]) : '' }}</span>
            <span v-if="value.template === 'html'" v-html="value.value"></span>
            <span v-if="value.template === 'item'">{{ item[value.value] ? item[value.value] : '' }}</span>
            <span v-if="value.template === 'view'">
               <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small fab color="shades" v-on="on" @click="openItem(item.id)">
                      <v-icon
                      >
                        {{ value.icon ? value.icon : 'mdi-information-variant' }}
                      </v-icon>
                    </v-btn>
                  </template>
                  <span>{{ value.text ? value.text : 'Просмотр' }}</span>
                </v-tooltip>
            </span>
            <span v-if="value.template === 'edit'">
               <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small fab color="primary" v-on="on" @click="editItem(item.id)">
                      <v-icon
                      >
                        {{ value.icon ? value.icon : 'mdi-pencil' }}
                      </v-icon>
                    </v-btn>
                  </template>
                  <span>{{ value.text ? value.text : 'Редактировать' }}</span>
                </v-tooltip>
            </span>
            <span v-if="value.template === 'remove'">
              <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small fab color="error" v-on="on" @click="removeItem(item.id)">
                      <v-icon
                      >
                        {{ value.icon ? value.icon : 'mdi-delete' }}
                      </v-icon>
                    </v-btn>
                  </template>
                  <span>{{ value.text ? value.text : 'Удалить' }}</span>
                </v-tooltip>
            </span>
          </td>
        </tr>
      </tbody>
  </v-simple-table>
  <v-card flat class="pl-5 mt-5" >
    <v-row>
      <v-col>
        <v-pagination
          v-model="params.page"
          :length="countPages"
          :total-visible="7"
          style="justify-content: left"
          circle
        ></v-pagination>
      </v-col>
      <v-col class="text-center">
        {{ pagination.data.length }} из {{ pagination.totalItems }}
      </v-col>
      <v-col class="pr-10">
            <v-select
              :items="itemsPerPageSelect"
              v-model="pagination.itemsPerPage"
              hide-selected
              class="pa-0 ma-0"
              single-line
              small-chips
              :hint="itemsPerPageText"
              persistent-hint
              style="max-width: 100px;position:absolute;right: 1rem"
            >
            </v-select>
      </v-col>
    </v-row>
  </v-card>
</div>
</template>

<script>

export default {
  name: 'PaginatorTable',
  props: {
    pagination: {
      type: Object
    },
    headers: {
      type: Array
    }
  },
  data () {
    return {
      date: null,
      itemsPerPageSelect: [5, 10, 25, 50],
      itemsPerPageText: 'записей на странице',
      datepickers: {},
      params: {
        itemsPerPage: null,
        sort: '',
        direction: 'desc',
        page: 1
      }
    }
  },
  computed: {
    countPages: function () {
      let count = this.pagination.totalItems / this.pagination.itemsPerPage
      return Math.ceil(count)
    }
  },
  watch: {
    'pagination.itemsPerPage': function (val) {
      this.params.itemsPerPage = val
      this.$emit('update', this.params)
    },
    'params.direction': function (val) {
      this.$emit('update', this.params)
    },
    'params.page': function (val) {
      this.$emit('update', this.params)
    }
  },
  methods: {
    formatDateDatepicker (date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },
    openItem (item) {
      this.$emit('view-item', item)
    },
    editItem (item) {
      this.$emit('edit-item', item)
    },
    removeItem (item) {
      this.$emit('remove-item', item)
    },
    sortItems (item) {
      this.params.sort = item
      this.params.direction === 'desc' ? this.params.direction = 'asc' : this.params.direction = 'desc'
    },
    datepickerAccept (date, filter, key) {
      this.params[filter] = this.formatDateDatepicker(date)
      this.datepickers['menu' + key] = false
      this.date = null
    },
    acceptFilter () {
      if (this.params.page === 1) {
        this.$emit('update', this.params)
      } else {
        this.params.page = 1
      }
    },
    clearFilter () {
      this.headers.forEach((value, index) => {
        this.params[value.filter] = ''
      })
      if (this.params.page === 1 && this.params.sort === '') {
        this.$emit('update', this.params)
      } else {
        this.params.sort = ''
        this.params.page = 1
      }
    },
    formatDate (date) {
      return new Intl.DateTimeFormat('ru-RU', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour12: false,
        timeZone: 'Europe/Moscow' }).format(new Date(date))
    },
    formatDateTime (date) {
      return new Intl.DateTimeFormat('ru-RU', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: false,
        timeZone: 'Europe/Moscow' }).format(new Date(date))
    }
  },
  mounted () {
    this.params.itemsPerPage = this.pagination.itemsPerPage
  }
}
</script>

<style scoped>

</style>
