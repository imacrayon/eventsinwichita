<template>
  <form style="padding: .75rem 0 1rem 0;">
    <div class="grid-x grid-padding-x">
      <div class="cell">

        <label for="filter-tags" class="">Tags</label>
        <div id="filter-tags" class="tags-field">
          <template v-for="tag in tags">
            <input type="checkbox" name="tags[]" :value="tag.id" v-model="filters.tags" :id="`filter-tag-${tag.id}`">
            <label class="button hollow tiny" :for="`filter-tag-${tag.id}`">{{ tag.name }}</label>
          </template>
        </div>

        <button class="button" style="margin: 0;">Filter</button>
        <button type="button" class="button transparent" style="margin: 0;" @click="reset" v-show="query">Reset</button>
      </div>
    </div>
  </form>
</template>

<script>
import { getSearchParam, toArray } from '../../helpers'

export default {
  data () {
    return {
      filters: {
        tags: false
      },

      tags: false,
    }
  },

  created () {
    this.filters.tags = toArray(getSearchParam('tags', []))
    this.getTags()
  },

  methods: {
    /**
     * Get all tags.
     */
    getTags () {
      return axios.get('/api/tags')
        .then(response => {
          this.tags = response.data
          return response
        })
    },

    reset () {
      window.location = window.location.href.split('?')[0]
    }
  }
}
</script>
