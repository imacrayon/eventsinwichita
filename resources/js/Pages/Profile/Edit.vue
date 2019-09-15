<template>
  <layout :title="form.name">
    <div class="-mx-4">
      <div class="bg-white rounded shadow max-w-lg mt-8 mx-auto">
        <form @submit.prevent="submit">
          <div class="p-8">
            <h1 class="text-center font-bold text-2xl">My Profile</h1>
            <div class="mx-auto mt-4 w-24 border-b-2" />
            <div class="-mr-6 -mb-8 flex flex-wrap">
              <input-field
                v-model="form.name"
                :errors="$page.errors.name"
                class="mt-6 pr-6 pb-8 w-full"
                label="Name"
                name="name"
                required
                autofocus
              />
              <input-field
                name="email"
                v-model="form.email"
                :errors="$page.errors.email"
                label="Email"
                type="email"
                autocapitalize="off"
                class="pr-6 pb-8 w-full"
                required
              />
              <input-field
                name="password"
                v-model="form.password"
                label="New Password"
                type="password"
                class="pr-6 pb-8 w-full"
              />
              <input-field
                name="password_confirmation"
                v-model="form.password_confirmation"
                label="Confirm New Password"
                type="password"
                class="pr-6 pb-8 w-full"
              />
            </div>
          </div>
          <div
            class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center rounded-b"
          >
            <loading-button :loading="sending" class="btn" type="submit"
              >Update Profile</loading-button
            >
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import InputField from '@/Shared/Fields/InputField'

export default {
  components: {
    Layout,
    LoadingButton,
    InputField,
  },

  props: {
    user: Object,
  },

  data: self => ({
    sending: false,
    form: {
      name: self.user.name,
      email: self.user.email,
      password: null,
      password_confirmed: null,
    },
  }),

  methods: {
    submit() {
      this.sending = true
      this.$inertia
        .post(this.route('profile.update'), this.form)
        .then(() => (this.sending = false))
    },
  },
}
</script>
