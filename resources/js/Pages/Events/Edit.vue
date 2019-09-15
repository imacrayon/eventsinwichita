<template>
  <layout :title="`Edit ${form.name}`">
    <div class="-mx-4">
      <div
        class="overflow-hidden bg-white rounded shadow max-w-2xl mt-8 mx-auto"
      >
        <form @submit.prevent="submit">
          <div class="p-8">
            <trashed-message
              v-if="event.deleted_at"
              class="mb-6"
              :restorable="true"
              @restore="restore"
            >
              This event has been deleted.
            </trashed-message>
            <h1 class="font-bold text-2xl leading-tight">{{ form.name }}</h1>
            <div class="mt-6 w-24 border-b-2" />
            <div class="-mr-6 -mb-8 flex flex-wrap">
              <input-field
                v-model="form.name"
                :errors="$page.errors.name"
                class="mt-6 pr-6 pb-8 w-full"
                label="Event Name"
                name="name"
                required
                autofocus
              />
              <date-field
                v-model="startDate"
                :errors="$page.errors.start"
                class="pr-6 pb-8 w-full"
                label="Start Date"
                name="startDate"
                required
              />
              <time-field
                v-model="startTime"
                class="pr-6 pb-8 w-1/2"
                label="Start Time"
                name="startTime"
                required
              />
              <time-field
                v-model="endTime"
                :errors="$page.errors.end"
                class="pr-6 pb-8 w-1/2"
                label="End Time"
                name="endTime"
              />
              <time-zone-input
                v-model="form.timezone"
                name="timezone"
              ></time-zone-input>

              <auto-complete-field
                v-model="form.location"
                :errors="$page.errors.location"
                class="pr-6 pb-8 w-full"
                label="Location"
                name="location"
                :options="locations"
                required
              />
              <textarea-field
                v-model="form.description"
                :errors="$page.errors.description"
                class="pr-6 pb-8 w-full"
                label="Description"
                name="name"
                rows="3"
                autosize
              />
            </div>
          </div>
          <div
            class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center rounded-b"
          >
            <button
              type="button"
              tabindex="-1"
              class="hover:underline"
              @click="destroy"
            >
              Delete Event
            </button>
            <loading-button :loading="sending" class="btn" type="submit"
              >Update Event</loading-button
            >
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout'
import EventDate from '@/mixins/EventDate'
import DateField from '@/Shared/Fields/DateField'
import TimeField from '@/Shared/Fields/TimeField'
import TimeZoneInput from '@/Shared/TimeZoneInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'
import InputField from '@/Shared/Fields/InputField'
import TextareaField from '@/Shared/Fields/TextareaField'
import AutoCompleteField from '@/Shared/Fields/AutoCompleteField'

export default {
  components: {
    Layout,
    LoadingButton,
    InputField,
    TextareaField,
    DateField,
    TimeField,
    TimeZoneInput,
    AutoCompleteField,
    TrashedMessage,
  },

  mixins: [EventDate],

  props: {
    event: Object,
    locations: Array,
  },

  remember: 'form',

  data: self => ({
    sending: false,
    startDate: self.event.start,
    startTime: self.event.start,
    endTime: self.event.end,
    form: {
      name: self.event.name,
      start: null,
      end: null,
      location: self.event.location,
      description: self.event.description,
    },
  }),

  methods: {
    submit() {
      this.sending = true
      this.$inertia
        .put(
          this.route('events.update', this.event),
          Object.assign(this.form, this.formatEventTimes())
        )
        .then(() => (this.sending = false))
    },

    destroy() {
      if (confirm('Are you sure you want to delete this event?')) {
        this.$inertia.delete(this.route('events.destroy', this.event.id))
      }
    },

    restore() {
      if (confirm('Are you sure you want to restore this event?')) {
        this.$inertia.put(this.route('events.restore', this.event.id))
      }
    },
  },
}
</script>
