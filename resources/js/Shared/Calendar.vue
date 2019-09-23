<template>
  <div class="calendar">
    <date-time-input
      ref="input"
      class="block opacity-0 invisible absolute"
      :inline="true"
      :enableTime="false"
      :enable="enable"
      :value="localizedValue"
      @change="handleChange"
    />
    <div class="text-right">
      <button
        type="button"
        class="text-sm mt-4 mr-3 text-gray-600 hover:text-gray-900 focus:text-gray-900"
        @click="reset"
      >
        Reset
      </button>
    </div>
  </div>
</template>

<script>
import DateTimeInput from '@/Shared/DateTimeInput'
import InteractsWithDates from '@/mixins/InteractsWithDates'

export default {
  components: {
    DateTimeInput,
  },

  mixins: [InteractsWithDates],

  props: {
    enable: {
      type: Array,
      default: () => [],
    },
  },

  data: self => ({ localizedValue: self.fromAppTimezone(self.value) }),

  methods: {
    handleChange(value) {
      this.localizedValue = value
      this.$emit('input', this.toAppTimezone(value))
    },
    reset() {
      this.$refs.input.flatpickr.setDate(null, true)
    },
  },
}
</script>
