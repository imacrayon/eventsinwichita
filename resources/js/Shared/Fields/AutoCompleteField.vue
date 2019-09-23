<template>
  <BaseField
    :name="name"
    :label="label"
    :required="$attrs.required"
    :helpText="helpText"
    :errors="errors"
  >
    <autocomplete
      auto-select
      :search="search"
      :default-value="value"
      @submit="handleChange"
    >
      <template
        v-slot="{
          rootProps,
          inputProps,
          inputListeners,
          resultListProps,
          resultListListeners,
          results,
          resultProps,
        }"
      >
        <div v-bind="rootProps">
          <input
            v-bind="inputProps"
            v-on="inputListeners"
            class="form-input mt-1 block w-full"
          />
          <ul
            class="list-none bg-white shadow-lg overflow-y-auto"
            v-bind="resultListProps"
            v-on="resultListListeners"
          >
            <li
              v-for="(result, index) in results"
              :key="resultProps[index].id"
              v-bind="resultProps[index]"
              class="px-3 py-2 leading-normal cursor-default flex"
            >
              <icon
                name="search"
                class="w-4 h-4 text-gray-500 inline-block mr-2 flex-shrink-0 mt-1"
              />
              {{ result }}
            </li>
          </ul>
        </div>
      </template>
    </autocomplete>
  </BaseField>
</template>

<script>
import _ from 'lodash'
import Fuse from 'fuse.js'
import Icon from '@/Shared/Icon'
import BaseField from './BaseField'
import FormField from '@/mixins/FormField'
import Autocomplete from '@trevoreyre/autocomplete-vue'

export default {
  components: {
    Icon,
    BaseField,
    Autocomplete,
  },

  mixins: [FormField],

  props: {
    options: Array,
  },

  data: self => ({
    engine: new Fuse(self.options, {
      shouldSort: true,
      threshold: 0.4,
      location: 0,
      distance: 100,
      maxPatternLength: 32,
      minMatchCharLength: 3,
    }),
    results: [],
  }),

  methods: {
    search(query) {
      this.handleChange(query)
      return this.engine.search(query).map(key => this.options[key])
    },

    handleChange(value) {
      this.$emit('input', value)
    },
  },
}
</script>
