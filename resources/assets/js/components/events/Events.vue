<template>
  <div>

    <div class="grid-container grid-container-padded" v-if="events === false">
      <loader>Fetching events</loader>
    </div>

    <div class="grid-container grid-container-padded" v-if="events !== false && Object.keys(events).length === 0">
      <h4 class="text-medium-gray">There aren't any events right now. Go read a book.</h4>
    </div>

    <transition-group name="fade" tag="div">
      <div v-for="(day, index) in calendar" :key="index">
        <div class="sticky">
            <div class="sticky-fix calendar-day">
                <div class="grid-container grid-container-padded" v-html="displayCalendarDay(index)">
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
      if (this.events === false) return {}

      const setPlacementData = (event, day) => {
        event.calendar_day = day
        event.starts_today = false
        event.ends_today = false

       /*
        * Notes About sort_score
        *
        * We score events based on the number of minutes since 00:00
        * Certain types of events get bonus points based on 1440 minutes
        * 1440 is the total number of minutes in a day
        *
        * Events happening all day + 0 (sorted by ending soonest)
        * Events ending on this day + 1440 (sorted by ending soonest)
        * Events starting and ending on this day + 2880 (sorted by upcoming)
        *
        * The bonus points ensure that events that are happening
        * all day long get listed first, then events ending on this day,
        * and finally "normal" events that start and end on the same day.
        */

        // Events happening all day or ending on this day are
        // sorted by end_time
        const startOfEndDay = moment(event.end_time).startOf('day')
        event.sort_score = event.end_time.diff(startOfEndDay) / 60000

        if (event.end_time.isSame(day, 'day')) {
          event.ends_today = true
          // Bonus points for events ending on this day
          event.sort_score += 1440
        }

        if (event.start_time.isSame(day, 'day')) {
          event.starts_today = true
          // Events starting on this day are sorted by start_time
          const startOfStartDay = moment(event.start_time).startOf('day')
          event.sort_score = event.start_time.diff(startOfStartDay) / 60000
          // Bonus points for events starting and ending on the same day
          event.sort_score += 2880
        }
      }

      /*
       * Adds an event to an array of events for each day that it
       * occurs based on it's the range of its start and end time.
       *
       * @param  Object events  The master list of events.
       * @param  Object event  A single event with a start and end time.
       * @param  Moment day The day to check if the event occurs on (with time 11::59:59).
       */
      const splitEventDays = (events, event, day = null) => {
        day = day || moment(event.start_time).endOf('day')
        // Ignore any events that fall outside the filter
        if (day.isSameOrAfter(moment(this.filters.start_time), 'day')) {
          let date = day.format('YYYY-MM-DD')
          events[date] = events[date] || []
          event = Object.assign({}, event)
          setPlacementData(event, day)
          events[date].push(event)
        }
        if (event.end_time.isAfter(day)) {
          splitEventDays(events, event, moment(day).add(1, 'day'))
        }
      }

      let calendar = this.events.reduce((events, event) => {
        event.start_time = moment(event.start_time)
        event.end_time = moment(event.end_time)
        splitEventDays(events, event)

        return events
      }, {})

      // Sort by start time ignoring the day & year of the DateTime
      Object.keys(calendar).map(key => {
        calendar[key].sort((a, b) => a.sort_score - b.sort_score)
      })

      return calendar
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
      window.flash('Events filtered.')
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

    displayCalendarDay (day) {
      day = moment(day)
      if (day.isSame(moment(), 'day')) {
        return `Today &middot ${day.format('ddd MMM DD')}`
      } else {
        return day.format('ddd MMM DD')
      }
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
