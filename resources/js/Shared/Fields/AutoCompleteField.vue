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

<style>
.autocomplete {
  & input[aria-expanded='true'] {
    outline: none;
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.16);
    border-color: theme('colors.black');
    background-color: theme('colors.white');
  }

  & ul {
    max-height: 9rem;
    border: 1px solid theme('colors.black');
    & > li {
      &:hover,
      &[aria-selected='true'] {
        background-color: theme('colors.gray.100');
      }
    }
  }

  &[data-position='below'] {
    & input[aria-expanded='true'] {
      border-bottom-color: transparent;
      border-radius: 4px 4px 0 0;
    }
    & > ul {
      margin-top: -1px;
      border-top-color: transparent;
      border-radius: 0 0 4px 4px;
    }
  }

  &[data-position='above'] {
    & input[aria-expanded='true'] {
      position: relative;
      border-top-color: transparent;
      border-radius: 0 0 4px 4px;
      z-index: 2;
    }
    & > ul {
      margin-bottom: -1px;
      border-bottom-color: transparent;
      border-radius: 4px 4px 0 0;
    }
  }
}
</style>
