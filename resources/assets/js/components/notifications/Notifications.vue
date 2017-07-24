<template>
  <div>

    <div class="grid-container grid-container-padded" v-if="notifications === false">
      <loader>Fetching notifications</loader>
    </div>

    <div class="grid-container grid-container-padded" v-if="notifications !== false && notifications.length === 0">
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
          class="notification"
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

  </div>
</template>

<script>
import moment from 'moment'
import Loader from '../Loader.vue'
import { diffForHumans } from '../../helpers'

export default {
  components: { Loader },

  data () {
    return {
      notifications: false,

      user: window.App.user
    }
  },

  computed: {
    calendar () {
      if (this.notifications === false) {
        return {}
      }
      return this.notifications.reduce((notifications, notification) => {
        notification.created_at = moment.utc(notification.created_at)
        const date = (notification.created_at.isSame(moment(), 'day') ? 'Today &middot; ' : '')
          + notification.created_at.format('ddd MMM DD')
        notifications[date] = notifications[date] || []
        notifications[date].push(notification)
        return notifications
      }, {})
    }
  },

  created () {
    this.getNotifications()
  },

  methods: {
    diffForHumans,

    getNotifications () {
      axios.get('/api/users/' + this.user.id + '/notifications')
      .then(({ data }) => {
        this.notifications = data
      })
    },

    markAsRead (notification) {
      axios.delete('/api/users/' + this.user.id + '/notifications/' + notification.id)
        .then(response => {
          this.getNotifications()
        })
    }
  }
}
</script>
