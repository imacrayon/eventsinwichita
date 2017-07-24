<template>
  <ul class="pagination text-center" style="margin-top: 1rem;" role="navigation" aria-label="Pagination" v-if="shouldPaginate">
    <li :class="['pagination-previous', {'disabled': !prevUrl}]">
      <a v-if="prevUrl" href="#" aria-label="Previous page" rel="prev" @click.prevent="page--">
        Previous
      </a>
      <span v-else>Previous</span>
    </li>
    <li :class="['pagination-next', {'disabled': !nextUrl}]">
      <a v-if="nextUrl" href="#" aria-label="Next page" rel="next" @click.prevent="page++">
        Next
      </a>
      <span v-else>Next</span>
    </li>
  </ul>
</template>

<script>
  export default {
    props: ['pageData'],

    data() {
      return {
        page: 1,
        prevUrl: false,
        nextUrl: false
      }
    },

    watch: {
      pageData () {
        this.page = this.pageData.current_page;
        this.prevUrl = this.pageData.prev_page_url;
        this.nextUrl = this.pageData.next_page_url;
      },

      page () {
        this.broadcast().updateUrl()
      }
    },

    computed: {
      shouldPaginate () {
        return !!this.prevUrl || !!this.nextUrl
      }
    },

    methods: {
      broadcast () {
        return this.$emit('changed', this.page)
      },

      updateUrl () {
        history.pushState(null, null, '?page=' + this.page)
      }
    }
  }
</script>
