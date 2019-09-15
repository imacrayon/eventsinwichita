<template>
  <BaseField
    :name="name"
    :label="label"
    :required="$attrs.required"
    :helpText="helpText"
    :errors="errors"
  >
    <date-time-input
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
import BaseField from './BaseField'
import FormField from '@/mixins/FormField'
import DateTimeInput from '../DateTimeInput'
import InteractsWithDates from '@/mixins/InteractsWithDates'

export default {
  components: {
    BaseField,
    DateTimeInput,
  },

  mixins: [FormField, InteractsWithDates],

  data: self => ({ localizedValue: self.fromAppTimezone(self.value) }),

  methods: {
    handleChange(value) {
      this.localizedValue = value
      this.$emit('input', this.toAppTimezone(value))
    },
  },
}
</script>
