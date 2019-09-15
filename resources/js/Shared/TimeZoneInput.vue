<template>
  <input type="hidden" :value="userTimezone" />
</template>

<script>
import InteractsWithDates from '@/mixins/InteractsWithDates'

export default {
  mixins: [InteractsWithDates],

  created() {
    this.$emit('input', this.userTimezone)
  },
}
</script>
