<template>
  <div class="place" :id="place.id">
    <div class="place-section">
      <h1 class="place-name">
        <a :href="'/places/' + place.id">
          {{ place.name }}
        </a>
      </h1>
      <a v-if="place.street" class="place-detail" target="_blank" :href="place.map">
        <svg class="icon"><use xlink:href="/images/icons.svg#icon-pin"></use></svg>
        {{ place.street }}
      </a>
      <div class="place-tags" v-if="place.tags.length > 0">
        <span v-for="tag in place.tags" class="label" :key="tag.id">{{ tag.name }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { parseUrlHost } from '../../helpers'

export default {
  props: ['place'],

  methods: {
    parseUrlHost
  }
}
</script>
