import { InertiaApp } from '@inertiajs/inertia-vue'
import PortalVue from 'portal-vue'
import Vue from 'vue'

Vue.config.productionTip = false
Vue.mixin({ methods: { route: window.route } })
Vue.filter('title', function(value) {
  if (!value) return ''

  return value.toString().replace(/\s+(\S+)$/, '&#160;$1')
})
Vue.use(InertiaApp)
Vue.use(PortalVue)

const app = document.getElementById('app')

new Vue({
  render: h =>
    h(InertiaApp, {
      props: {
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: name =>
          import(`@/Pages/${name}`).then(module => module.default),
      },
    }),
}).$mount(app)
