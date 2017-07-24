<template>
  <a @click="subscribe" :class="classes">
    <svg class="icon"><use xlink:href="/images/icons.svg#icon-eye"></use></svg>
    <span v-text="this.isActive ? 'Watching' : 'Watch'"></span>
  </a>
</template>

<script>
export default {
  props: ['active'],

  data () {
    return {
      isActive: this.active
    }
  },

  computed: {
    classes() {
      return [this.isActive ? 'active' : '']
    }
  },

  created () {
    window.events.$on(
      'comment-created', (comment) => this.isActive = true
    )
  },

  methods: {
    subscribe() {
      axios[
        (this.isActive ? 'delete' : 'post')
      ](`/api${location.pathname.replace(location.origin, '')}/subscriptions`)

      this.isActive = !this.isActive
      window.flash(this.isActive ? 'You will receive notifications about activity on this page.' : 'Notifications for this page have been turned off.')
    }
  }
}
</script>
