<template>
  <div class="event" :id="event.id">
    <div class="event-section">
      <time class="event-start" :datetime="event.start_time.format()" :title="event.start_time.format('dddd, MMMM Do YYYY, h:mm:ss a')">
        <span class="event-start-day-name">{{ event.start_time.format('ddd') }}</span>
        <span class="event-start-day">{{ event.start_time.format('MM D') }}</span>
        <span class="event-start-time">{{ event.start_time.format('h:mm A') }}</span>
      </time>
      <time class="event-end" :datetime="event.end_time.format()" :title="event.end_time.format('dddd, MMMM Do YYYY, h:mm:ss a')">
        <span class="event-end-day-name">{{ event.end_time.format('ddd') }}</span>
        <span class="event-end-day">{{ event.end_time.format('MM D') }}</span>
        <span class="event-end-time">{{ event.end_time.format('h:mm A') }}</span>
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
      <div class="event-tags" v-if="event.tags.length > 0">
        <span v-for="tag in event.tags" class="label" :key="tag.id">{{ tag.name }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['event']
}
</script>
