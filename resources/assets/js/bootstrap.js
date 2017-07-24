window.Vue = require('vue');

window.Vue.prototype.authorize = function (handler) {
    // Additional admin privileges here.
    const user = window.App.user

    return user ? handler(user) : false
};

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.App.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

window.events = new Vue();

window.flash = (body, level = 'success') => {
  window.events.$emit('flash', {body, level})
}

window.error = message => {
  window.events.$emit('error', message)
}
