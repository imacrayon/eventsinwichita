<template>
  <transition name="modal">
    <div class="modal-overlay" tabindex="-1" role="dialog">
      <div class="modal-container">
        <div class="modal">
          <slot></slot>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  created () {
    this.escListener = (e) => {
      if (e.keyCode === 27) {
        this.close()
      }
    }
    document.addEventListener('keydown', this.escListener)
  },

  beforeDestroy () {
    document.removeEventListener('keydown', this.escListener)
  },

  methods: {
    overlayClick (e) {
      if (e.target.classList.contains('modal-overlay')) {
        this.close()
      }
    },
    close () {
      document.removeEventListener('keydown', this.escListener)
      this.$emit('close')
    }
  }
}
</script>
