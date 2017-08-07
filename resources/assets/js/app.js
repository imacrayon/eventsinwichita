
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

Vue.component('error', require('./components/Error.vue'))

Vue.component('flash', require('./components/Flash.vue'))

Vue.component('events-page', require('./components/pages/EventsPage.vue'));

Vue.component('event-page', require('./components/pages/EventPage.vue'));

Vue.component('events', require('./components/events/Events.vue'));

Vue.component('places-page', require('./components/pages/PlacesPage.vue'));

Vue.component('place-page', require('./components/pages/PlacePage.vue'));

Vue.component('places', require('./components/places/Places.vue'));

Vue.component('activities', require('./components/activities/Activities.vue'));

Vue.component('comments', require('./components/comments/Comments.vue'));

Vue.component('dropdown', require('./components/Dropdown.vue'));

Vue.component('notifications', require('./components/notifications/Notifications.vue'))

Vue.component('notifications-dropdown', require('./components/notifications/NotificationsDropdown.vue'))

import { serialize, getSearchParam, toArray } from './helpers'

const app = new Vue({
  el: '#app',
  data () {
    return {
      filters: {
        start_time: getSearchParam('start_time', ''),
        end_time: getSearchParam('end_time', ''),
        tags: getSearchParam('tags', []),
        user_id: getSearchParam('user_id', ''),
        place_id: getSearchParam('place_id', '')
      }
    }
  },
  created () {
    this.filters.tags = toArray(this.filters.tags)

    window.onpopstate = event => {
      this.filters = event.state || this.filters
    }
    window.events.$on('filter', filters => {
      // If the search string is different that the current one
      this.filters = Object.assign(this.filters, filters)
      const search = serialize(this.filters)
      if (search && search !== window.location.href.split('?')[1]) {
        window.history.pushState(this.filters, window.document.title, search ? `?${search}` : location.pathname)
      }
    })
    // Initialize the app history
    const search = serialize(this.filters)
    window.history.pushState(this.filters, window.document.title, search ? `?${search}`: location.pathname)
  }
})
