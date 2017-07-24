<template>
  <div>

    <div class="grid-container grid-container-padded" v-if="events === false">
      <loader>Fetching events</loader>
    </div>

    <div class="grid-container grid-container-padded" v-if="events !== false && Object.keys(events).length === 0">
      <h4 class="text-medium-gray">There aren't any events right now. Go read a book.</h4>
    </div>

  <transition-group name="fade" tag="div">
    <div v-for="(day, date) in calendar" :key="date">
      <div class="sticky">
          <div class="sticky-fix calendar-day">
              <div class="grid-container grid-container-padded" v-html="date">
              </div>
          </div>
      </div>
      <div class="grid-container grid-container-padded">
          <event v-for="event in day" :key="event.id" :event="event" @edit="$emit('edit', event)"></event>
      </div>
    </div>
  </transition-group>

    <div class="grid-container grid-container-padded" v-if="showNextWeek">
      <div class="grid-x grid-margin-x align-center">
        <div class="small-12 medium-6 cell">
          <a class="button expanded" style="margin: 2rem 0 2rem 0;" @click="nextWeek">Next Week</a>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
import moment from 'moment'
import Event from './Event.vue'
import Loader from '../Loader.vue'
import { serialize, getSearchParam, setSearchParam, formatUrlDate } from '../../helpers'

export default {
  components: { Loader, Event },

  props: ['scope'],

  data () {
    return {
      filters: Object.assign({
        start_time: null,
        end_time: null,
        tags: null,
        user_id: null,
        place_id: null
      }, this.scope),

      events: false
    }
  },

  computed: {
    calendar () {
      const formatString = 'ddd MMM DD'
      if (this.events === false) {
        return {}
      }
      const startFilter = moment(this.filters.start_time)
      const startOfToday = moment().startOf('day')
      const endOfToday = moment().endOf('day')
      const todaysDate = moment().format(formatString)
      let date = ''
      return this.events.reduce((events, event) => {
        event.start_time = moment(event.start_time)
        event.end_time = moment(event.end_time)
        date = event.start_time.format(formatString)
        if (event.start_time.isBefore(endOfToday)
          && event.end_time.isAfter(startOfToday)) {
          date = startFilter.isAfter(startOfToday)
            ? `Today &middot; ${todaysDate}`
            : `${date} &middot; Happening Today`
        }
        events[date] = events[date] || []
        events[date].push(event)
        return events
      }, {})
    },

    showNextWeek () {
      return !!this.filters.end_time && this.events.length > 0
    },
  },

  created () {
    this.filters.start_time = this.getFilter('start_time', formatUrlDate(moment()))
    this.filters.end_time = this.getFilter('end_time', formatUrlDate(moment(this.filters.start_time).add(6, 'days')))
    this.filters.user_id = this.getFilter('user_id', '')
    this.filters.place_id = this.getFilter('place_id', '')
    const tags = this.getFilter('tags', [])
    this.filters.tags = Array.isArray(tags) ? tags : [tags]

    this.getEvents()
  },

  methods: {
    getFilter (name, defaultVal) {
      return this.filters[name] !== null
        ? this.filters[name]
        : getSearchParam(name, defaultVal)
    },

    /**
     * Get all events.
     */
    getEvents () {
      return axios.get(`/api/events?${serialize(this.filters)}`)
        .then(({data}) => {
          this.events = data
          return data
        })
    },

    nextWeek () {
      this.filters.end_time = formatUrlDate(moment(this.filters.end_time).add(7, 'days'))
      this.getEvents()
      this.updateUrl()
    },

    updateUrl () {
      history.pushState(null, null, '?' + setSearchParam('end_time', this.filters.end_time))
    }

  }
}
</script>
