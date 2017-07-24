<template>
  <div class="comments">

    <div class="grid-container grid-container-padded" v-if="items === false">
      <loader>Fetching comments</loader>
    </div>

    <div class="grid-container grid-container-padded" v-if="items !== false && items.length === 0">
      <h4 class="text-medium-gray">
        Be the first to post a comment.
      </h4>
    </div>

    <div class="grid-container grid-container-padded" v-if="items.length > 0">
      <comment v-for="(comment, index) in items" :key="comment.id"
        :comment="comment"
        :admin="admin"
        @destroy="remove(index)"
      ></comment>
    </div>

    <paginator :pageData="pageData" @changed="getComments"></paginator>

    <comments-form @store="add"></comments-form>

  </div>
</template>

<script>
import Loader from '../Loader.vue'
import Comment from './Comment.vue'
import Paginator from '../Paginator.vue'
import CommentsForm from './CommentsForm.vue'
import collection from '../../mixins/collection';

export default {
  props: ['admin'],

  components: { Loader, Comment, Paginator, CommentsForm },

  mixins: [collection],

  data () {
    return {
      pageData: false
    }
  },

  created() {
    this.getComments()
  },

  methods: {
    getComments (page) {
        axios.get(this.url(page)).then(this.refresh)
    },

    url (page) {
      if (!page) {
        let query = location.search.match(/page=(\d+)/)
        page = query ? query[1] : 1;
      }
      return `/api${location.pathname.replace(location.origin, '')}/comments?page=${page}`
    },

    refresh ({data}) {
      this.pageData = data
      this.items = data.data
      window.scrollTo(0, 0)
    }
  }

}
</script>
