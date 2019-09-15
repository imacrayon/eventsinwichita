<template>
  <time
    :datetime="localizedValue.toISO()"
    :title="localizedValue.toLocaleString()"
  >
    <template v-if="format == 'time'">
      {{ localizedValue.toFormat('h:mm a') }}
    </template>
    <template v-else-if="format == 'full'">
      {{ localizedValue.toFormat('DDDD') }}
    </template>
  </time>
</template>

<script>
import { DateTime } from 'luxon'
import InteractsWithDates from '@/mixins/InteractsWithDates'

export default {
  mixins: [InteractsWithDates],

  props: {
    value: String,
    format: {
      type: String,
      default: 'event',
    },
  },

  data: self => ({
    localizedValue: self.fromAppTimezone(self.value, null),
  }),
}
</script>
