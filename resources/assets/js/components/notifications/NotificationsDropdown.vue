<template>
  <dropdown>
    <a href="#" class="dropdown">
      <svg class="icon"><use xlink:href="/images/icons.svg#icon-bell"></use></svg>
      <span class="badge">{{ count }}</span>
    </a>

    <div slot="menu">
      <ul class="vertical menu">
        <li v-for="notification in notifications" v-if="notifications.length > 0">
          <a class="notification"
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
        </li>
        <li v-else>
          <a>All notifications are read</a>
        </li>
      </ul>
      <div class="callout transparent text-center" style="margin: 0;">
        <a :href="url()">
          <strong>All notifications</strong>
        </a>
      </div>
    </div>
  </dropdown>
</template>

<script>
import { diffForHumans } from '../../helpers'

export default {
  data () {
    return {
      count: 0,

      notifications: false,

      user: window.App.user
    }
  },

  created () {
    this.getNotifications()
  },

  methods: {
    diffForHumans,

    url () {
      return `/users/${this.user.id}/notifications`
    },

    getNotifications () {
      axios.get('/api/users/' + this.user.id + '/notifications')
      .then(({ data }) => {
        // Paginated
        this.count = data.data.length
        this.notifications = data.data.slice(0, 5)
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
