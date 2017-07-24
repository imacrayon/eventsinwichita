
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

const app = new Vue({
    el: '#app'
});

require('what-input')
