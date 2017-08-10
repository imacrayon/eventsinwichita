<template>
  <div>

    <div class="grid-container grid-container-padded" v-if="items === false">
      <loader>Fetching notifications</loader>
    </div>

    <div class="grid-container grid-container-padded" v-if="items !== false && items.length === 0">
        <h4 class="text-medium-gray">There aren't any notifications right now.</h4>
    </div>

    <template v-for="(day, key) in calendar">
      <div class="sticky">
          <div class="sticky-fix calendar-day">
              <div class="grid-container grid-container-padded" v-html="key">
              </div>
          </div>
      </div>
      <div class="grid-container grid-container-padded">
        <a
          v-for="notification in day"
          :key="notification.id"
          :class="{'notification': true, 'read': notification.read_at !== null}"
          :href="notification.data.url"
          @click="markAsRead(notification)"
        >
          <div class="notification-body">
            {{ notification.data.message }}
          </div>
          <div class="notification-meta">
            {{ diffForHumans(notification.created_at) }}
          </div>
        </a>
      </div>
    </template>

    <paginator :pageData="pageData" @changed="getNotifications"></paginator>

  </div>
</template>

<script>
import moment from 'moment'
import Loader from '../Loader.vue'
import Paginator from '../Paginator.vue'
import { diffForHumans } from '../../helpers'
import collection from '../../mixins/collection';

export default {
  components: { Loader, Paginator },

  mixins: [collection],

  data () {
    return {
      pageData: false,
    }
  },

  computed: {
    calendar () {
      if (this.items === false) {
        return {}
      }
      return this.items.reduce((items, item) => {
        item.created_at = moment.utc(item.created_at)
        const date = (item.created_at.isSame(moment(), 'day') ? 'Today &middot; ' : '')
          + item.created_at.format('ddd MMM DD')
        items[date] = items[date] || []
        items[date].push(item)
        return items
      }, {})
    }
  },

  created () {
    this.getNotifications()
  },

  methods: {
    diffForHumans,

    getNotifications (page) {
      axios.get(this.url(page)).then(this.refresh)
    },

    url (page) {
      if (!page) {
        let query = location.search.match(/page=(\d+)/)
        page = query ? query[1] : 1;
      }
      return `/api${location.pathname.replace(location.origin, '')}?read=true&page=${page}`
    },

    markAsRead (notification) {
      axios.delete(`/api${location.pathname.replace(location.origin, '')}/${notification.id}`)
        .then(response => {
          this.getNotifications()
        })
    },

    refresh ({data}) {
      this.pageData = data
      this.items = data.data
      window.scrollTo(0, 0)
    }
  }
}
</script>
