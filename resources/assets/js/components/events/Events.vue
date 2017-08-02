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
      events: false
    }
  },

  computed: {
    filters () {
      let filters = Object.assign({}, this.$root.filters, this.scope)
      filters.tags = Array.isArray(filters.tags) ? filters.tags : [filters.tags]
      filters.start_time = filters.start_time || formatUrlDate(moment())
      // Don't mess with the end time if there are tag filters
      if (filters.tags.length === 0) {
        filters.end_time = filters.end_time || formatUrlDate(moment(filters.start_time).add(6, 'days'))
      }

      return filters
    },

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
    }
  },

  created () {
    this.getEvents()
  },

  watch: {
    filters () {
      this.getEvents()
    }
  },

  methods: {
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
