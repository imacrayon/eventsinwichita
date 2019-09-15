<template>
  <div class="p-6 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-sm">
      <logo class="block mx-auto w-full max-w-xs" height="98" />
      <form
        class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden"
        @submit.prevent="submit"
      >
        <div class="p-10">
          <h1 class="text-center font-bold text-2xl">New Profile</h1>
          <div class="mx-auto mt-4 w-24 border-b-2" />
          <p class="mt-6 text-center">
            Already have a profile?
            <inertia-link :href="route('login')" class="underline font-bold"
              >Login</inertia-link
            >.
          </p>
          <input-field
            name="name"
            v-model="form.name"
            :errors="$page.errors.name"
            label="Your Name"
            type="name"
            class="mt-6"
            required
            autofocus
          />
          <input-field
            name="email"
            v-model="form.email"
            :errors="$page.errors.email"
            label="Your Email"
            type="email"
            autocapitalize="off"
            class="mt-6"
            required
          />
          <input-field
            name="password"
            v-model="form.password"
            label="Password"
            type="password"
            class="mt-6"
            required
          />
          <input-field
            name="password_confirmation"
            v-model="form.password_confirmation"
            label="Confirm Password"
            type="password"
            class="mt-6"
            required
          />
        </div>
        <div
          class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center"
        >
          <loading-button :loading="sending" class="btn" type="submit"
            >Create Profile</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import InputField from '@/Shared/Fields/InputField'
import LoadingButton from '@/Shared/LoadingButton'
import Logo from '@/Shared/Logo'

export default {
  components: {
    InputField,
    LoadingButton,
    Logo,
  },
  props: {
    errors: Object,
  },
  data: () => ({
    sending: false,
    form: {
      name: null,
      email: null,
      password: null,
      password_confirmation: null,
    },
  }),
  mounted() {
    document.title = 'New Profile | Events in Wichita'
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia
        .post(this.route('register.attempt'), this.form)
        .then(() => (this.sending = false))
    },
  },
}
</script>
