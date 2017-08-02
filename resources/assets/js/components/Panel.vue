<template>
  <transition name="panel">
    <div class="panel-wrapper">
      <div class="panel-overlay" @click="close()"></div>
      <div class="panel">
        <slot></slot>
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
    close () {
      document.removeEventListener('keydown', this.escListener)
      this.$emit('close')
    }
  }
}
</script>
