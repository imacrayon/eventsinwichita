<template>
  <div class="event" :id="event.id">
    <div class="event-section">
      <time class="event-start" :datetime="event.start_time.format()" :title="event.start_time.format('dddd, MMMM Do YYYY, h:mm:ss a')">
        <span class="event-start-day-name">{{ event.start_time.format('ddd') }}</span>
        <span class="event-start-day">{{ event.start_time.format('MM D') }}</span>
        <span class="event-start-time" v-if="this.event.starts_today">
          {{ event.start_time.format('h:mm A') }}
        </span>
        <span class="event-start-time" v-else-if="this.event.ends_today">
          Ends
        </span>
        <span class="event-start-time" v-else>
          All Day
        </span>
      </time>
      <time class="event-end" :datetime="event.end_time.format()" :title="event.end_time.format('dddd, MMMM Do YYYY, h:mm:ss a')">
        <span class="event-end-day-name">{{ event.end_time.format('ddd') }}</span>
        <span class="event-end-day">{{ event.end_time.format('MM D') }}</span>
        <span class="event-end-time" v-if="event.ends_today">
          {{ event.end_time.format('h:mm A') }}
        </span>
        <span class="event-end-time" v-else-if="event.starts_today">
          &rarr; <a @click="filterByDate(event.end_time)">{{ event.end_time.format('M/D') }}</a>
        </span>
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
          <input type="checkbox" :id="`filter-tag-${event.id}-${tag.id}`" :value="tag.id" :checked="$root.filters.tags.indexOf(tag.id) !== -1" @change="filterByTag">
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
    filterByDate (date) {
      window.filter({start_time: formatUrlDate(date)})
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
    }
  }
}
</script>
