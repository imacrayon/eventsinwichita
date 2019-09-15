<template>
  <div class="max-w-3xl xl:max-w-5xl mx-auto px-4 my-8">
    <portal-target name="dropdown" slim />
    <header class="mx-auto flex justify-between items-center sm:px-2">
      <inertia-link :href="route('events.index')"><logo /></inertia-link>
      <div class="flex items-center flex-shrink-0 ml-4">
        <inertia-link
          :href="route('events.create')"
          class="text-gray-600 hover:text-gray-900"
          >Add Event</inertia-link
        >
        <dropdown class="ml-4" placement="bottom-end" v-if="$page.auth.user">
          <div class="flex items-center cursor-pointer select-none group">
            <div
              class="text-gray-800 group-hover:text-red-600 focus:text-red-600 mr-1 whitespace-no-wrap"
            >
              <img
                class="block w-8 h-8 rounded-full"
                :src="$page.auth.user.avatar"
                :alt="$page.auth.user.name"
              />
            </div>
            <icon
              class="w-5 h-5 group-hover:fill-red-600 fill-gray-800 focus:fill-red-600"
              name="cheveron-down"
            />
          </div>
          <div slot="menu" class="mt-2 py-2 shadow-lg bg-white rounded text-sm">
            <inertia-link
              class="block px-6 py-2 hover:bg-gray-100"
              :href="route('profile.edit')"
              >My Profile</inertia-link
            >
            <inertia-link
              class="block px-6 py-2 hover:bg-gray-100"
              :href="route('logout')"
              method="post"
              >Logout</inertia-link
            >
          </div>
        </dropdown>
        <inertia-link
          v-else
          :href="route('login')"
          class="text-gray-600 hover:text-gray-900 ml-8"
          >Login</inertia-link
        >
      </div>
    </header>
    <flash-messages />
    <slot />
  </div>
</template>

<script>
import Logo from '@/Shared/Logo'
import Icon from '@/Shared/Icon'
import Dropdown from '@/Shared/Dropdown'
import FlashMessages from '@/Shared/FlashMessages'

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Logo,
  },
  props: {
    title: String,
  },
  watch: {
    title: {
      immediate: true,
      handler(title) {
        document.title = title
          ? `${title} | Events in Wichita`
          : 'Events in Wichita'
      },
    },
  },
}
</script>
