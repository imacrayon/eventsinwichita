<template>
  <BaseField
    :name="name"
    :label="label"
    :required="$attrs.required"
    :helpText="helpText"
    :errors="errors"
  >
    <date-time-input
      :enable-date="false"
      :date-format="dateFormat"
      :id="name"
      class="form-input mt-1 block w-full"
      :value="localizedValue"
      v-bind="inputAttributes"
      @change="handleChange"
    />
    <input
      type="hidden"
      :name="name"
      :value="toAppTimezone(this.localizedValue)"
    />
  </BaseField>
</template>

<script>
import { DateTime } from 'luxon'
import BaseField from './BaseField'
import FormField from '@/mixins/FormField'
import DateTimeInput from '../DateTimeInput'
import InteractsWithDates from '@/mixins/InteractsWithDates'
import { FORMAT_TIME } from '@/mixins/InteractsWithDates'

export default {
  components: {
    BaseField,
    DateTimeInput,
  },

  mixins: [FormField, InteractsWithDates],

  data: self => ({
    localizedValue: self.fromAppTimezone(self.value, FORMAT_TIME),
    dateFormat: FORMAT_TIME,
  }),

  methods: {
    handleChange(value) {
      this.localizedValue = value
      this.$emit('input', this.toAppTimezone(value, FORMAT_TIME))
    },
  },
}
</script>
