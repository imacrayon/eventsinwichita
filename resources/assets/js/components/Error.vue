<template>
  <modal v-if="visible" @close="visible = false" class="alert">
    <div class="modal-head">
      <div class="modal-title">Error</div>
    </div>
    <div class="modal-section">
      <p>{{ body }}</p>
    </div>
    <div class="modal-foot">
      <div class="modal-foot-right">
        <button type="button" class="button" @click="hide">Close</button>
      </div>
    </div>
  </modal>
</template>

<script>
  import Modal from './Modal.vue'

  export default {
    components: { Modal },

    props: ['message'],

    data () {
      return {
        body: '',
        visible: false
      }
    },

    created () {
      if (this.message) {
        this.show(this.message)
      }

      window.events.$on(
        'error', message => this.show(message)
      )
    },

    methods: {
      show (message) {
        this.body = message
        this.visible = true
      },

      hide () {
        this.visible = false
      }
    }
  }
</script>
