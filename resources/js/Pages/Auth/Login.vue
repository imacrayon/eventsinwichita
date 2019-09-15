<template>
  <div class="p-6 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-sm">
      <logo class="block mx-auto w-full max-w-xs" height="98" />
      <form
        class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden"
        @submit.prevent="submit"
      >
        <div class="p-10">
          <h1 class="text-center font-bold text-2xl">Welcome Back!</h1>
          <div class="mx-auto mt-4 w-24 border-b-2" />
          <p class="mt-6 text-center">
            New here?
            <inertia-link :href="route('register')" class="underline font-bold"
              >Create a profile</inertia-link
            >.
          </p>
          <input-field
            name="email"
            v-model="form.email"
            :errors="$page.errors.email"
            label="Email"
            type="email"
            class="mt-6"
            autocapitalize="off"
            required
            autofocus
          />
          <input-field
            name="password"
            v-model="form.password"
            label="Password"
            type="password"
            class="mt-6"
            required
          />
          <checkbox-field
            name="remember"
            v-model="form.remember"
            label="Remember Me"
            class="mt-6"
            type="checkbox"
          />
        </div>
        <div
          class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center"
        >
          <inertia-link
            class="hover:underline"
            tabindex="-1"
            :href="route('password.request')"
            >Forgot password?</inertia-link
          >
          <loading-button :loading="sending" class="btn" type="submit"
            >Login</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import CheckboxField from '@/Shared/Fields/CheckboxField'
import InputField from '@/Shared/Fields/InputField'
import LoadingButton from '@/Shared/LoadingButton'
import Logo from '@/Shared/Logo'

export default {
  components: {
    CheckboxField,
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
      password: null,
      remember: null,
    },
  }),
  mounted() {
    document.title = 'Login | Events in Wichita'
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia
        .post(this.route('login.attempt'), {
          email: this.form.email,
          password: this.form.password,
          remember: this.form.remember,
        })
        .then(() => (this.sending = false))
    },
  },
}
</script>
