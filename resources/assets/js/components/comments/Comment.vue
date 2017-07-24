<template>
  <div class="comment" :id="'comment-' + comment.id">
    <div
    <div class="comment-section">
      <img class="comment-image" :src="comment.user.avatar" :alt="comment.user.name" width="40" height="40">
    </div>
    <div class="comment-section">
      <strong class="comment-name">{{ comment.user.name }}</strong>
      <div class="comment-body">
        <div v-if="editing">
          <form @submit.prevent="update">
            <textarea :id="'comment-' + comment.id +'-form'" v-model="form.body" required></textarea>

            <button class="button loading" :disabled="form.isSubmitting()" @click="update">
              Update
            </button>

            <button class="button transparent" @click="cancel" type="button">
              Cancel
            </button>
          </form>
        </div>

        <div v-else v-html="safeHtml"></div>
      </div>
      <div class="comment-meta">
        <a :href="url">
          {{ diffForHumans(comment.created_at) }}
        </a>
        <span v-if="isEdited">(edited)</span>
        <template v-if="canUpdate">
          &middot;
          <button class="action-link" @click="edit">Edit</button>
        </template>
        <template v-if="canDestroy">
          &middot;
          <button class="action-link" @click="confirm">Destroy</button>
        </template>
      </div>
    </div>

    <modal v-if="confirming">
      <div class="modal-head">
        <div class="modal-title">Confirm Destruction</div>
      </div>
      <div class="modal-section">
        <p>Are you super sure you want to destroy this comment?</p>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-right">
          <button class="button transparent"
            @click="confirming = false"
            :disabled="form.isSubmitting()">
            Keep
          </button>
          <button class="button loading"
            @click="destroy"
            :disabled="destroying">
            Destroy
          </button>
        </div>
      </div>
    </modal>

  </div>
</template>

<script>
import Modal from '../Modal.vue'
import autosize from 'autosize'
import anchorme from 'anchorme';
import Form from '../../utilities/Form'
import { diffForHumans, urlMap } from '../../helpers'

export default {
  props: ['comment', 'admin'],

  components: { Modal },

  data () {
    return {
      editing: false,

      confirming: false,

      destroying: false,

      attributes: this.comment,

      form: new Form({
        body: this.comment.body
      })
    }
  },

  computed: {
    safeHtml () {
      return anchorme(this.attributes.body.replace(/(?:\r\n|\r|\n)/g, '<br>'))
    },

    isEdited () {
      return this.attributes.updated_at !== this.attributes.created_at
    },

    canUpdate () {
      return this.authorize(user => this.comment.user_id === user.id)
    },

    canDestroy () {
      return this.authorize(user => this.comment.user_id === user.id || user.id === this.admin)
    },

    url () {
      return urlMap[this.comment.subject_type] + this.comment.subject_id + '#comment-' + this.comment.id
    },
  },

  methods: {
    diffForHumans,

    cancel () {
      this.editing = false
      this.form.body = this.attributes.body
    },

    edit () {
      this.editing = true
      this.form.body = this.attributes.body
      this.$nextTick(() => {
        autosize(document.getElementById('comment-' + this.comment.id +'-form'))
      })
    },

    update () {
      this.form.put('/api/comments/' + this.comment.id)
        .then(data => {
          this.attributes = data
          this.editing = false
          flash('Comment updated.')
        })
    },

    confirm () {
      this.confirming = true
    },

    destroy () {
      this.destroying = true
      return axios.delete('/api/comments/' + this.comment.id)
        .then(({data}) => {
          this.destroying = false
          this.$emit('destroy', this.attributes)
          return data
        })
    }
  }
}
</script>
