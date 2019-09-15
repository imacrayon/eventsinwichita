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

<style>
.calendar .flatpickr-calendar {
  background-color: transparent;
  box-shadow: none;
  color: theme('colors.gray.600');
  & .flatpickr-weekday,
  & select,
  & input {
    color: theme('colors.gray.600');
  }
  & .flatpickr-day {
    color: theme('colors.gray.800');
    &:hover {
      background: theme('colors.gray.300');
    }
    &.flatpickr-disabled {
      color: theme('colors.gray.500');
      &:hover {
        background: transparent;
      }
    }
    &.today {
      background: theme('colors.gray.300');
      &:hover {
        background: theme('colors.gray.300');
      }
      &:not(.selected) {
        border-color: transparent;
      }
    }
    &.selected {
      color: theme('colors.white');
      background: theme('colors.gray.600');
      border-color: theme('colors.gray.600');
      &:hover {
        color: theme('colors.white');
        background: theme('colors.gray.600');
        border-color: theme('colors.gray.600');
      }
    }
  }
  & .flatpickr-prev-month,
  & .flatpickr-next-month {
    & svg {
      fill: theme('colors.gray.600');
    }
    &:hover svg {
      fill: theme('colors.gray.800');
    }
  }
}
</style>
