<template>
  <BaseField
    :name="name"
    :label="label"
    :required="$attrs.required"
    :helpText="helpText"
    :errors="errors"
  >
    <textarea
      ref="input"
      :id="name"
      :name="name"
      class="form-textarea mt-1 block w-full"
      :value="value"
      @input="$emit('input', $event.target.value)"
      v-bind="inputAttributes"
    />
  </BaseField>
</template>

<script>
import autosize from 'autosize'
import BaseField from './BaseField'
import FormField from '@/mixins/FormField'

export default {
  components: { BaseField },

  mixins: [FormField],

  props: {
    autosize: {
      type: Boolean,
      default: false,
    },
  },

  mounted() {
    if (this.autosize) {
      autosize(this.$refs.input)
    }
  },

  computed: {
    defaultAttributes() {
      return {
        autofocus: this.$attrs.autofocus,
        class: this.errorClasses,
        placeholder: this.$attrs.placeholder,
        rows: this.$attrs.rows,
      }
    },
  },
}
</script>
