<template>
  <div class="p-6 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-sm">
      <logo class="block mx-auto w-full max-w-xs" height="98" />
      <form
        class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden"
        @submit.prevent="submit"
      >
        <div class="p-10">
          <h1 class="text-center font-bold text-2xl">Reset Password</h1>
          <div class="mx-auto mt-4 w-24 border-b-2" />
          <flash-messages />
          <input-field
            name="email"
            v-model="form.email"
            :errors="$page.errors.email"
            label="Email"
            type="email"
            class="mt-10"
            autocapitalize="off"
            required
            autofocus
          />
        </div>
        <div
          class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center"
        >
          <loading-button :loading="sending" class="btn" type="submit"
            >Send Reset Link</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import CheckboxField from '@/Shared/Fields/CheckboxField'
import FlashMessages from '@/Shared/FlashMessages'
import InputField from '@/Shared/Fields/InputField'
import LoadingButton from '@/Shared/LoadingButton'
import Logo from '@/Shared/Logo'

export default {
  components: {
    CheckboxField,
    FlashMessages,
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
      email: null,
    },
  }),
  mounted() {
    document.title = 'Password Reset | Events in Wichita'
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia
        .post(this.route('password.email'), {
          email: this.form.email,
        })
        .then(() => (this.sending = false))
    },
  },
}
</script>
