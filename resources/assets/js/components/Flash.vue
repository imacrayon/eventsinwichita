<template>
  <transition name="slide-fade">
    <div v-show="show" :class="'callout flash ' + level" role="alert">
      <svg class="icon"><use :xlink:href="`/images/icons.svg#${icon}`"></use></svg>
      {{ body }}
    </div>
  </transition>
</template>

<script>
  export default {
    props: ['message'],

    data () {
      return {
        level: 'success',
        body: '',
        show: false
      }
    },

    computed: {
      icon () {
        if (this.level === 'success') {
          return 'icon-success'
        } else if (this.level === 'alert') {
          return 'icon-alert'
        } else if (this.level === 'warning') {
          return 'icon-warning'
        } else {
          return 'icon-bullhorn'
        }
      }
    },

    created () {
      if (this.message) {
        this.show({body: this.message, level: this.level})
      }

      window.events.$on(
        'flash', (data) => this.show(data)
      )
    },

    methods: {
      show({body, level}) {
        this.level = level
        this.body = body
        this.show = true

        this.hide()
      },

      hide() {
        setTimeout(() => {
          this.show = false
        }, 5000)
      }
    }
  }
</script>
