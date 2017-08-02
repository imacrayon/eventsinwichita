<template>
  <form ref="form" class="grid-y" style="height:100%;">
    <div class="cell auto" style="padding: .75rem; overflow:auto;">

      <label for="filter-tags" class="">Filter By Date</label>
      <date-picker name="start_time" id="start_time"
        :config="{
          defaultDate: new Date(),
          enableTime: false,
          altInput: false,
          static: true,
          inline: true,
          minDate: new Date(),
        }"
        v-model="filters.start_time"
        @input="submit()"
      ></date-picker>

      <label for="filter-tags" class="">Filter By Tag</label>
      <div id="filter-tags" class="tags-field">
        <template v-for="tag in tags">
          <input type="checkbox" name="tags[]" :value="tag.id" v-model="filters.tags" :id="'filter-tag-' + tag.id">
          <label :for="'filter-tag-' + tag.id">{{ tag.name }}</label>
        </template>
      </div>

    </div>
    <div class="cell shrink">

      <div style="padding: .75rem; border-top: 1px solid #cecece; box-shadow: 0 0 8px rgba(0, 0, 0, .15);">
        <button type="button" class="button expanded hollow" style="margin-bottom:.5rem;" @click="reset" v-show="query">Reset</button>
        <button class="button expanded" style="margin-bottom:0">Filter</button>
      </div>

    </div>
  </form>
</template>

<script>
import DatePicker from '../DatePicker.vue'
import { getSearchParam } from '../../helpers'

export default {
  components: { DatePicker },

  data () {
    return {
      filters: {
        start_time: getSearchParam('start_time', ''),
        end_time: getSearchParam('end_time', ''),
        tags: getSearchParam('tags', [])
      },

      query: window.location.search,

      tags: false
    }
  },

  created () {
    this.filters.tags = Array.isArray(this.filters.tags)
      ? this.filters.tags
      : [this.filters.tags]
    this.getTags()

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

    submit () {
      this.$refs.form.submit()
    },

    reset () {
      window.location = window.location.href.split('?')[0]
    }
  }
}
</script>
