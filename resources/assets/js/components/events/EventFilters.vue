<template>
  <div>
    <panel v-if="panel" @close="$emit('close')">
        <div class="panel-section" style="padding-bottom: 4rem;">

          <label for="filter-tags" class="">Filter By Date</label>
          <date-picker name="start_time" id="start_time"
            :config="{
              enableTime: false,
              altInput: false,
              static: true,
              inline: true,
              minDate: new Date(),
            }"
            :value="$root.filters.start_time"
            @input="filterByDate"
          ></date-picker>

          <label for="filter-tags">Filter By Tag</label>
          <div id="filter-tags" class="tags-field">
            <template v-for="tag in tags">
              <input type="checkbox" name="tags[]" :id="`filter-tag-${tag.id}`" :value="tag.id" :checked="$root.filters.tags.indexOf(tag.id) !== -1" @change="filterByTag">
              <label :for="`filter-tag-${tag.id}`">{{ tag.name }}</label>
            </template>
          </div>

        </div>
    </panel>

    <div class="filters-reset" v-show="dirty">
      <div class="grid-container">
        <div class="grid-x grid-padding-x align-right">
          <button type="button" class="button" style="margin-bottom:0;" @click="reset">Clear Filters</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import Panel from '../Panel.vue'
import DatePicker from '../DatePicker.vue'
import { getSearchParam, formatUrlDate } from '../../helpers'

export default {
  components: { DatePicker, Panel },

  props: ['panel'],

  data () {
    return {
      tags: false
    }
  },

  computed: {
    dirty () {
      // Have to join arrays before comparing because of Object identity
      return this.$root.filters.start_time !== '' || this.$root.filters.tags.join(',') !== ''
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

    filterByDate (date) {
      window.filter({start_time: date})
    },

    filterByTag (e) {
      const tags = this.$root.filters.tags
      if (e.target.checked === true) {
        tags.push(Number(e.target.value))
      } else {
        const index = tags.indexOf(Number(e.target.value))
        tags.splice(index, 1)
      }
      window.filter({tags: tags})
    },

    reset () {
      window.filter({
        start_time: '',
        tags: []
      })
    }
  }
}
</script>
