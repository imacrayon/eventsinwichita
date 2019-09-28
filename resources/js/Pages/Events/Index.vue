<template>
  <layout>
    <div class="flex justify-center -mx-4 mt-8">
      <div class="flex-grow">
        <div class="bg-white shadow rounded pt-8">
          <template v-if="Object.keys(days).length">
            <div
              v-for="(day, key) in days"
              :key="key"
              class="relative leading-none"
            >
              <formatted-time
                class="block sticky top-0 left-0 z-10 text-sm px-4 sm:px-6 py-2 sm:py-3 bg-gray-100 border-b border-t leading-none text-gray-700"
                :value="day[0].start.toISO()"
                format="full"
              />
              <article
                v-for="event in day"
                :key="event.id"
                class="event relative hover:bg-gray-100 p-4 sm:p-6"
              >
                <formatted-time
                  :value="event.start.toISO()"
                  format="time"
                  class="text-sm leading-none"
                />
                <div>
                  <h2 class="leading-tight sm:text-lg font-medium mt-2">
                    <inertia-link
                      :href="route('events.show', event)"
                      class="block"
                      v-html="$options.filters.title(event.name)"
                    />
                  </h2>
                  <div class="text-gray-600 leading-tight text-sm mt-2">
                    {{ event.location }}
                  </div>
                </div>
              </article>
            </div>
          </template>
          <template v-else>
            <div
              class="text-6xl text-gray-300 p-4 sm:p-6 font-bold text-center"
            >
              No events found.
            </div>
          </template>
        </div>
        <div class="px-6 sm:px-12">
          <loading-button
            class="btn mt-8 mb-8 w-full"
            :loading="fetching"
            @click="addOneWeek"
          >
            Next Week
          </loading-button>
        </div>
      </div>
      <div class="hidden ml-8 xl:block">
        <calendar
          :value="form.after"
          @input="setAfter"
          :enable="dates.map(date => new Date(date))"
        />
      </div>
    </div>
  </layout>
</template>

<script>
import _ from 'lodash'
import Layout from '@/Shared/Layout'
import FormattedTime from '@/Shared/FormattedTime'
import Calendar from '@/Shared/Calendar'
import LoadingButton from '@/Shared/LoadingButton'
import { DateTime } from 'luxon'
import InteractsWithDates from '@/mixins/InteractsWithDates'
import { FORMAT_MYSQL } from '@/mixins/InteractsWithDates'

export default {
  components: {
    Layout,
    Calendar,
    LoadingButton,
    FormattedTime,
  },

  mixins: [InteractsWithDates],

  props: ['events', 'dates'],

  watch: {
    form: {
      handler: _.throttle(function() {
        let query = _.pickBy(this.form)
        this.$inertia
          .replace(
            this.route(
              'events.index',
              Object.keys(query).length ? query : { remember: 'forget' }
            ),
            { preserveScroll: true }
          )
          .then(() => (this.fetching = false))
      }, 150),
      deep: true,
    },
  },

  data: self => ({
    fetching: false,
    form: {
      after: '',
      before: null,
    },
  }),

  computed: {
    days() {
      const days = this.events.reduce((events, event) => {
        event.start = this.fromAppTimezone(event.start, null)
        event.end = this.fromAppTimezone(event.end, null)

        return this.repeatEvent(events, event)
      }, {})

      return _.forEach(days, day => _.sortBy(day, 'ranking'))
    },
  },

  methods: {
    setAfter(value) {
      this.form = {
        after: value,
        before: null,
      }
    },

    repeatEvent(events, event, day = null) {
      day = day || event.start.endOf('day')
      const after = this.form.after
        ? this.form.after
        : DateTime.local()
            .toUTC()
            .toFormat(FORMAT_MYSQL)
      if (day >= DateTime.fromSQL(after, { zone: 'UTC' })) {
        let key = day.toFormat('yyyy-MM-dd')
        events[key] = events[key] || []
        events[key].push(this.rankEvent(event, day))
      }
      if (
        this.form.before &&
        day < event.end &&
        day < DateTime.fromSQL(this.form.before, { zone: 'UTC' })
      ) {
        events = this.repeatEvent(events, event, day.plus({ days: 1 }))
      }

      return events
    },

    rankEvent(event, day) {
      const MINUTES_IN_DAY = 1440
      event.starts_today = false
      event.ends_today = false

      if (event.end.hasSame(day, 'day')) {
        event.ends_today = true
        event.sort_score += MINUTES_IN_DAY
      }

      if (event.start.hasSame(day, 'day')) {
        event.starts_today = true
        const startOfStartDay = event.start.startOf('day')
        event.ranking = event.start.diff(
          event.start.startOf('day'),
          'minutes'
        ).minutes
        event.ranking += MINUTES_IN_DAY * 2
      }

      return event
    },

    addOneWeek() {
      this.fetching = true
      if (!this.form.before) {
        this.form.before = DateTime.local()
          .plus({ weeks: 2 })
          .startOf('day')
          .toUTC()
          .toFormat(FORMAT_MYSQL)
      } else {
        this.form.before = DateTime.fromSQL(this.form.before, { zone: 'UTC' })
          .plus({ weeks: 1 })
          .startOf('day')
          .toFormat(FORMAT_MYSQL)
      }
    },
  },
}
</script>
