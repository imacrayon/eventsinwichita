<template>
  <div class="comment">
    <div class="comment-section">
      <img class="comment-image" :src="user ? user.avatar : '/images/avatar.png'" :alt="user ? user.name : 'Avatar'" width="40" height="40">
    </div>
    <div class="comment-section main-section">
      <div class="grid-container grid-container-padded">
        <template v-if="user">
          <form @submit.prevent="comment" @input="form.errors.clear($event.target.name)">

            <label :class="{'is-invalid-label': form.errors.has('body')}">
              <span class="show-for-sr">Comment</span>
              <textarea rows="1" id="comment-body" v-model="form.body" :class="{'is-invalid-input': form.errors.has('body')}" placeholder="Leave a comment"></textarea>
              <span :class="['form-error', {'is-visible': form.errors.has('body')}]" v-text="form.errors.get('body')"></span>
            </label>

            <button class="button loading float-right" style="margin: 0;width:160px;" :disabled="form.isSubmitting()">
              Comment
            </button>

          </form>
        </template>
        <template v-else>
          <label :class="{'is-invalid-label': form.errors.has('body')}">
            <span class="show-for-sr">Comment</span>
            <textarea rows="1"  v-model="form.body" placeholder="Post a comment" disabled></textarea>
          </label>
          <a href="/login" class="button loading float-right" style="margin: 0;">
            Log In to Comment
          </a>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import Form from '../../utilities/Form'
import autosize from 'autosize'

export default {
  data () {
    return {
      form: new Form({
        body: ''
      }),

      user: window.App.user
    }
  },

  mounted () {
    this.$nextTick(() => {
      autosize(document.getElementById('comment-body'))
    })
  },

  methods: {
    comment () {
      this.form.post(`/api${location.pathname.replace(location.origin, '')}/comments`)
        .then(data => {
          this.$emit('store', data)
          window.events.$emit('comment-created', data)
        })
    }
  }

}
</script>
