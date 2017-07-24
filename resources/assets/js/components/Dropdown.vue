<template>
  <div class="dropdown-component">
    <slot ></slot>
    <div class="dropdown-pane" :class="[{'is-open': isOpen}, size]">
      <div x-arrow></div>
      <slot name="menu"></slot>
    </div>
  </div>
</template>

<script>
import Popper from 'popper.js'

export default {
  props: ['buttonText', 'buttonClass', 'size'],

  data () {
    return {
      isOpen: true, // Set to `false` when mounted
      pane: null,
      button: null,
      popper: null,
      _closeEvent: null,
    }
  },

  mounted () {
    this.$nextTick(() => {
      this.button = this.$el.querySelector('.dropdown')
      this.pane = this.$el.querySelector('.dropdown-pane')
      this.button.addEventListener('click', this.toggle)
      this.button.setAttribute('aria-haspopup', true)

      this.popper = new Popper(
        this.button,
        this.pane,
        {
          eventsEnabled: false,
          placement: 'bottom'
        }
      )

      this.$el.removeChild(this.pane)
      document.body.appendChild(this.pane)

      this._closeEvent = (e) => {
        if (
          e.target !== this.button &&
          !this.pane.contains(e.target) &&
          !this.button.contains(e.target) // profile box
        ) {
          this.close()
        }
      }

      // Let popper initialize and position before hiding it.
      this.isOpen = false
    })
  },

  methods: {
    toggle () {
      if (!this.isOpen) {
        this.open()
      } else {
        this.close()
      }
    },

    open () {
      window.removeEventListener('click', this._closeEvent, false)
      window.addEventListener('click', this._closeEvent, false)
      this.button.setAttribute('aria-expanded', true)
      this.isOpen = true
      this.popper.update()
    },

    close () {
      window.removeEventListener('click', this._closeEvent, false)
      this.button.setAttribute('aria-expanded', false)
      this.isOpen = false
    }
  },

  beforeDestroy () {
    this.popper.destroy()
    if (document.body.contains(this.pane)) {
      document.body.removeChild(this.pane)
    }
    this.button.removeEventListener('click', this.toggle)
    window.removeEventListener('click', this._closeEvent)
  }
}
</script>
