<template>
  <button
    :disabled="loading"
    class="flex items-center justify-center"
    v-on="$listeners"
  >
    <div v-if="loading" class="btn-spinner mr-2" />
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
  },
}
</script>
