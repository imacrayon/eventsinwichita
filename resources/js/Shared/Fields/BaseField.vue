<template>
  <div class="relative">
    <label class="inline-block" v-if="label" :for="name">
      {{ label }}
      <span
        v-if="required !== undefined && Boolean(required)"
        class="align-top text-sm text-gray-500"
        >*</span
      >
      <HelpText
        v-if="helpText"
        class="italic text-gray-500"
        v-html="helpText"
      />
    </label>
    <slot />
    <HelpText v-if="hasError" class="text-red-600" v-html="firstError" />
  </div>
</template>

<script>
import HelpText from './HelpText'
import FormField from '@/mixins/FormField'

export default {
  components: {
    HelpText,
  },

  mixins: [FormField],

  props: {
    required: { type: Boolean, default: false },
  },
}
</script>
