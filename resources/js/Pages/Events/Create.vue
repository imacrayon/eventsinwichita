<template>
  <layout title="Add Event">
    <div class="-mx-4">
      <div
        class="overflow-hidden bg-white rounded shadow max-w-2xl mt-8 mx-auto"
      >
        <form @submit.prevent="submit">
          <div class="p-8">
            <h1 class="font-bold text-2xl">Add Event</h1>
            <div class="mt-4 w-24 border-b-2" />
            <div
              v-if="!$page.auth.user"
              class="mt-6 bg-gray-100 shadow p-4 leading-normal"
            >
              <p>
                Your submission will be reviewed before it is displayed on
                this&#160;site.
              </p>
              <p>
                Alternatively, you can
                <inertia-link :href="route('login')" class="font-bold underline"
                  >login</inertia-link
                >
                or
                <inertia-link
                  :href="route('register')"
                  class="font-bold underline"
                  >register</inertia-link
                >
                to post your event immediately.
              </p>
            </div>
            <div class="-mr-6 -mb-8 flex flex-wrap">
              <input-field
                v-model="form.name"
                :errors="$page.errors.name"
                class="mt-6 pr-6 pb-8 w-full"
                label="Event Name"
                name="name"
                required
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
            class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center rounded-b"
          >
            <loading-button :loading="sending" class="btn" type="submit"
              >Create Event</loading-button
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
  },

  mixins: [EventDate],

  props: {
    event: Object,
    locations: Array,
  },

  remember: 'form',

  data: self => ({
    sending: false,
    startDate: '',
    startTime: '',
    endTime: '',
    form: {
      name: null,
      start: null,
      end: null,
      timezone: null,
      location: null,
      description: null,
    },
  }),

  methods: {
    submit() {
      this.sending = true
      this.$inertia
        .post(
          this.route('events.store'),
          Object.assign(this.form, this.formatEventTimes())
        )
        .then(() => (this.sending = false))
    },
  },
}
</script>
