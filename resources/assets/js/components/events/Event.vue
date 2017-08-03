<template>
  <div class="event" :id="event.id">
    <div class="event-section">
      <time class="event-start" :datetime="event.start_time.format()" :title="event.start_time.format('dddd, MMMM Do YYYY, h:mm:ss a')">
        <span class="event-start-day-name">{{ event.start_time.format('ddd') }}</span>
        <span class="event-start-day">{{ event.start_time.format('MM D') }}</span>
        <span class="event-start-time" v-html="displayStartTime()"></span>
      </time>
      <time class="event-end" :datetime="event.end_time.format()" :title="event.end_time.format('dddd, MMMM Do YYYY, h:mm:ss a')">
        <span class="event-end-day-name">{{ event.end_time.format('ddd') }}</span>
        <span class="event-end-day">{{ event.end_time.format('MM D') }}</span>
        <span class="event-end-time" v-html="displayEndTime()"></span>
      </time>
    </div>
    <div class="event-section">
      <h1 class="event-name">
        <a :href="'/events/' + event.id">
          {{ event.name }}
        </a>
      </h1>
      <a class="event-place" v-if="event.place" :href="`/places/${event.place.id}`">
        {{ event.place.name }}
      </a>
      <div class="event-tags tags-field">
        <template v-for="tag in event.tags">
          <input type="checkbox" name="tags[]" :value="tag.id" v-model="$root.filters.tags" :id="`filter-tag-${event.id}-${tag.id}`">
          <label :for="`filter-tag-${event.id}-${tag.id}`">{{ tag.name }}</label>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import { formatUrlDate } from '../../helpers'

export default {
  props: ['event'],

  methods: {
    displayStartTime () {
      if (this.event.starts_today) {
        return this.event.start_time.format('h:mm A')
      } else if (this.event.ends_today) {
        return 'Ends'
      } else {
        return 'All Day'
      }
    },

    displayEndTime () {
      if (this.event.ends_today) {
        return this.event.end_time.format('h:mm A')
      } else if (this.event.starts_today) {
        return `&rarr; <a href="/?start_time=${formatUrlDate(this.event.end_time)}">${this.event.end_time.format('M/D')}</a>`
      }
    }
  }
}
</script>
