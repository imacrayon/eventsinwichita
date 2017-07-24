<template>
  <div>

    <div class="grid-container grid-container-padded" v-if="places === false">
      <loader>Fetching places</loader>
    </div>

    <div class="grid-container grid-container-padded" v-if="places !== false && places.length === 0">
        <h4 class="text-medium-gray">There aren't any places right now.</h4>
    </div>

    <div class="grid-container grid-container-padded">
      <transition-group name="fade" tag="div" class="grid-x grid-margin-x grid-margin-y medium-up-2 large-up-3">
        <div v-for="place in places" :key="place.id" class="cell">
          <place :place="place"></place>
        </div>
      </transition-group>
    </div>

  </div>
</template>

<script>
import Place from './Place.vue'
import Loader from '../Loader.vue'
import { serialize, getSearchParam } from '../../helpers'

export default {
  components: { Loader, Place },

  props: {
    filters: {
      default () {
        return {
          user_id: '',
          tags: false
        }
      }
    }
  },

  data () {
    return {
      places: false
    }
  },

  created () {
    this.filters.user_id = this.filters.user_id || getSearchParam('user_id', '')
    const tags = this.filters.tags || getSearchParam('tags', [])
    this.filters.tags = Array.isArray(tags) ? tags : [tags]
    this.getPlaces()
  },

  methods: {
    /**
     * Get all places.
     */
    getPlaces () {
      const search = serialize(this.filters)
      return axios.get(`/api/places${search ? '?' + search : ''}`)
        .then(({data}) => {
          this.places = data
          return data
        })
    },
  }

}
</script>
