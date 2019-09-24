<template>
  <layout :title="event.name">
    <div class="flex justify-center mt-8 -mx-4">
      <article
        class="relative overflow-hidden bg-white rounded shadow max-w-2xl p-8"
      >
        <div
          v-if="event.deleted_at"
          class="mb-6 p-4 bg-yellow-400 rounded border border-yellow-600 flex items-center justify-between"
        >
          <div class="flex items-center">
            <icon
              name="trash"
              class="flex-no-shrink w-4 h-4 text-yellow-800 mr-2"
            />
            <div class="text-yellow-800">
              This event has been deleted.
            </div>
          </div>
        </div>
        <header>
          <h1
            class="font-bold text-2xl leading-tight"
            v-html="$options.filters.title(event.name)"
          />
        </header>
        <div class="mt-6 w-24 border-b-2" />
        <p class="flex mt-10 text-gray-700 leading-tight">
          <icon name="date" class="inline-block w-5 h-5 mr-2 flex-shrink-0" />
          <formatted-time :value="event.start" format="full" />
        </p>
        <p class="flex mt-4 text-gray-700 leading-tight">
          <icon name="time" class="inline-block w-5 h-5 mr-2 flex-shrink-0" />
          <span>
            <formatted-time :value="event.start" format="time" />
            <template v-if="event.end">
              &nbsp;–&nbsp;<formatted-time :value="event.end" format="time"/>
              <formatted-time
                v-if="event.start.substr(0, 7) !== event.end.substr(0, 7)"
                :value="event.end"
                format="full"
              >
              </formatted-time
            ></template>
          </span>
        </p>
        <p class="flex mt-4 text-gray-700 leading-tight">
          <icon
            name="location"
            class="inline-block w-5 h-5 mr-2 flex-shrink-0"
          />
          {{ event.location }}
        </p>
        <p
          v-for="source in event.sources"
          :key="source.id"
          class="flex mt-4 text-gray-700 leading-tight"
        >
          <icon name="link" class="inline-block w-5 h-5 mr-2 flex-shrink-0" />
          <a class="underline truncate" :href="source.url">{{ source.url }}</a>
        </p>
        <div
          class="mt-10 leading-normal rich-text"
          v-html="event.description"
        />
        <inertia-link
          :href="
            event.can.update
              ? route('events.edit', event)
              : route('events.proposals.create', event)
          "
          class="leading-none rounded-full bg-gray-100 absolute top-0 right-0 p-2 m-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200"
          ><icon name="pencil" class="inline-block w-3 h-3 align-baseline ml-1"
        /></inertia-link>
      </article>
    </div>
  </layout>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import FormattedTime from '@/Shared/FormattedTime'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Icon,
    Layout,
    FormattedTime,
    TrashedMessage,
  },

  props: {
    event: Object,
  },
}
</script>
