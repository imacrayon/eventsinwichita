export default {
  props: {
    label: String,
    name: { type: String, required: true },
    helpText: String,
    value: null,
    errors: {
      type: Array,
      default: () => [],
    },
  },

  computed: {
    errorClass() {
      return this.hasError ? ['border-red-500'] : []
    },

    hasError() {
      return this.errors.length
    },

    firstError() {
      if (this.hasError) {
        return this.errors[0]
      }
    },

    defaultAttributes() {
      return {
        autofocus: this.$attrs.autofocus,
        class: this.errorClass,
      }
    },

    inputAttributes() {
      return {
        ...this.defaultAttributes,
        ...this.$attrs,
      }
    },
  },
}
