<template>
  <div class="grid-y" style="height:100%;">
    <div class="cell auto" style="padding: .75rem; overflow:auto;">

      <label for="filter-tags" class="">Filter By Date</label>
      <date-picker name="start_time" id="start_time"
        :config="{
          enableTime: false,
          altInput: false,
          static: true,
          inline: true,
          minDate: new Date(),
        }"
        v-model="$root.filters.start_time"
      ></date-picker>

      <label for="filter-tags">Filter By Tag</label>
      <div id="filter-tags" class="tags-field">
        <template v-for="tag in tags">
          <input type="checkbox" name="tags[]" :value="tag.id" v-model="$root.filters.tags" :id="`filter-tag-${tag.id}`">
          <label :for="`filter-tag-${tag.id}`">{{ tag.name }}</label>
        </template>
      </div>

    </div>
    <div class="cell shrink">

      <div style="padding: .75rem; border-top: 1px solid #cecece; box-shadow: 0 0 8px rgba(0, 0, 0, .15);" v-show="dirty">
        <button type="button" class="button expanded" style="margin-bottom:0;" @click="reset">Reset</button>
      </div>

    </div>
  </div>
</template>

<script>
import DatePicker from '../DatePicker.vue'
import { getSearchParam } from '../../helpers'

export default {
  components: { DatePicker },

  data () {
    return {
      tags: false
    }
  },

  computed: {
    dirty () {
      // Have to join arrays before comparing because of Object identity
      return this.$root.filters.start_time !== this.$root.cleanFilters.start_time || this.$root.filters.tags.join(',') !== this.$root.cleanFilters.tags.join(',')
    }
  },

  created () {
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
      this.$root.filters.start_time = this.$root.cleanFilters.start_time
      this.$root.filters.tags = this.$root.cleanFilters.tags
    }
  }
}
</script>
