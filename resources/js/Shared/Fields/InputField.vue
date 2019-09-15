<template>
  <BaseField
    :name="name"
    :label="label"
    :required="$attrs.required"
    :helpText="helpText"
    :errors="errors"
  >
    <input
      :id="name"
      :name="name"
      class="form-input mt-1 block w-full"
      :value="value"
      @input="$emit('input', $event.target.value)"
      v-bind="inputAttributes"
    />
  </BaseField>
</template>

<script>
import BaseField from './BaseField'
import FormField from '@/mixins/FormField'

export default {
  components: {
    BaseField,
  },

  mixins: [FormField],

  computed: {
    defaultAttributes() {
      return {
        autofocus: this.$attrs.autofocus,
        class: this.errorClasses,
        max: this.$attrs.max,
        min: this.$attrs.min,
        pattern: this.$attrs.pattern,
        placeholder: this.$attrs.placeholder,
        step: this.$attrs.step,
        type: this.$attrs.type || 'text',
      }
    },
  },
}
</script>
