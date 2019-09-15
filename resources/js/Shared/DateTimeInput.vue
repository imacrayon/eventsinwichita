<template>
  <input
    type="text"
    :disabled="disabled"
    :class="{ '!cursor-not-allowed': disabled }"
    :value="value"
    ref="input"
    :placeholder="placeholder"
    @blur="onChange"
  />
</template>

<script>
import { DateTime } from 'luxon'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/themes/airbnb.css'
import { dateTokens } from '@/mixins/InteractsWithDates'

export default {
  props: {
    value: {
      required: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    dateFormat: {
      type: String,
      default: 'n/j/Y h:i K',
    },
    enableTime: {
      type: Boolean,
      default: true,
    },
    enableDate: {
      type: Boolean,
      default: true,
    },
    inline: {
      type: Boolean,
      default: false,
    },
    enable: {
      type: Array,
      default: () => [],
    },
  },

  data: self => ({
    flatpickr: null,
    placeholder: DateTime.local().toFormat(dateTokens(self.dateFormat)),
  }),

  mounted() {
    this.$nextTick(() => {
      this.flatpickr = flatpickr(this.$refs.input, {
        noCalendar: !this.enableDate,
        enableTime: this.enableTime,
        onClose: this.onChange,
        onChange: this.onChange,
        dateFormat: this.dateFormat,
        inline: this.inline,
        enable: this.enable,
        allowInput: true,
      })
    })
  },

  methods: {
    onChange(event) {
      this.$emit('change', this.$refs.input.value)
    },
  },
}
</script>

<style scoped>
.\!cursor-not-allowed {
  cursor: not-allowed !important;
}
</style>
