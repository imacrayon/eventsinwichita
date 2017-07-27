<template>
  <div>

    <div class="head-bar">
      <div class="head-bar-container">
          <h1 class="head-bar-title">{{ place.name }}</h1>
      </div>
    </div>

    <div class="top-bar">
      <div class="top-bar-container">
        <div class="top-bar-left">
          <ul class="menu">
            <li>
              <a :href="'/places/' + place.id + '/events'" class="active">
                <svg class="icon"><use xlink:href="/images/icons.svg#icon-list"></use></svg>
                Details
              </a>
            </li>
            <li>
              <a :href="'/places/' + place.id + '/events'">
                <span class="badge" v-text="place.upcoming_events_count"></span>
                Events
              </a>
            </li>
          </ul>
        </div>
        <div class="top-bar-right" v-if="authorize(user => user.id)">
          <ul class="menu">
            <li v-if="authorize(user => place.user_id === user.id)">
              <a href="#" @click="edit(place)">
                <svg class="icon"><use xlink:href="/images/icons.svg#icon-pencil"></use></svg>
                Edit
              </a>
            </li>
            <li>
              <subscribe-button :active="place.is_subscribed_to"></subscribe-button>
            </li>
          </ul>
        </div>
    </div>
    </div>

    <div class="grid-container grid-container-padded">
      <div class="grid-x grid-margin-x">

        <div class="cell large-4">

          <div class="profile-section" v-if="place.street">
            <h2 class="profile-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-pin"></use></svg>
              Location
            </h2>
            <a target="_blank" :href="place.map">
              {{ place.street }}<br>
              {{ place.city }}, {{ place.state }} {{ place.zip }}
            </a>
          </div>

          <div class="profile-section" v-if="place.profile.website">
            <h2 class="profile-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-earth"></use></svg>
              Website
            </h2>
            <a target="_blank" :href="place.profile.website">
                {{ parseUrlHost(place.profile.website) }}
            </a>
          </div>

          <div class="profile-section" v-if="place.facebook_id">
            <h2 class="profile-section-title">
              <svg class="icon" style="vertical-align: text-top;"><use xlink:href="/images/icons.svg#icon-facebook"></use></svg>
              Facebook
            </h2>
            <a target="_blank" :href="'http://www.facebook.com/' + place.facebook_id">
              Facebook Page <span class="show-for-sr">for {{ place.name }}</span>
            </a>
          </div>

          <div class="profile-section" v-if="place.profile.phone">
            <h2 class="profile-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-smartphone"></use></svg>
              Phone
            </h2>
            <a target="_blank" :href="'tel:' + place.profile.phone">
              {{ place.profile.phone }}
            </a>
          </div>

          <div class="profile-section" v-if="place.tags.length > 0">
            <h2 class="profile-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-tag"></use></svg>
              Tags
            </h2>
            <div class="place-tags">
              <span v-for="tag in place.tags" class="label">{{ tag.name }}</span>
            </div>
          </div>

          <div class="profile-section">
            <h2 class="profile-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-user"></use></svg>
              Posted By
            </h2>
            <a :href="'/users/' + place.user.id">
              {{ place.user.name }}
            </a>
          </div>

        </div>

        <div class="cell large-8">

          <div class="profile-section">
            <h2 class="profile-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-comment"></use></svg>
              Comments
            </h2>
            <comments :comments="place.comments"></comments>
          </div>

        </div>

      </div>
    </div>

    <div class="section bg-image splash text-center">
      <div class="grid-container grid-container-padded">
          <svg class="icon" style="font-size: 3rem;margin-bottom: .5rem;"><use xlink:href="/images/icons.svg#icon-flag"></use></svg>
          <h3>Do You Own This Place?</h3>
          <p>Please contact us and we will give you access to edit the info on this page.</p>
          <a class="button hollow white" href="/contact">
            Claim My Place
          </a>
      </div>
    </div>

    <!-- Edit Place Modal -->
    <modal v-if="editModal">
      <div class="modal-head">
        <div class="modal-title">Add Place Information</div>
      </div>
      <form @submit.prevent="update" @input="editForm.errors.clear($event.target.name)">
        <div class="modal-section">

          <label :class="{'is-invalid-label': editForm.errors.has('name')}">Name
            <input type="text" v-model="editForm.name" :class="{'is-invalid-input': editForm.errors.has('name')}"></textarea>
            <span :class="['form-error', {'is-visible': editForm.errors.has('name')}]" v-text="editForm.errors.get('name')"></span>
          </label>

          <label :class="{'is-invalid-label': editForm.errors.has('street')}">Street
            <input type="text" v-model="editForm.street" :class="{'is-invalid-input': editForm.errors.has('street')}"></textarea>
            <span :class="['form-error', {'is-visible': editForm.errors.has('street')}]" v-text="editForm.errors.get('street')"></span>
          </label>

          <div class="grid-x grid-margin-x">
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': editForm.errors.has('city')}">City
                <input type="text" v-model="editForm.city" :class="{'is-invalid-input': editForm.errors.has('city')}"></textarea>
                <span :class="['form-error', {'is-visible': editForm.errors.has('city')}]" v-text="editForm.errors.get('city')"></span>
              </label>

            </div>
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': editForm.errors.has('state')}">State
                <input type="text" v-model="editForm.state" :class="{'is-invalid-input': editForm.errors.has('state')}"></textarea>
                <span :class="['form-error', {'is-visible': editForm.errors.has('state')}]" v-text="editForm.errors.get('state')"></span>
              </label>

            </div>
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': editForm.errors.has('zip')}">Zip
                <input type="text" v-model="editForm.zip" :class="{'is-invalid-input': editForm.errors.has('zip')}"></textarea>
                <span :class="['form-error', {'is-visible': editForm.errors.has('zip')}]" v-text="editForm.errors.get('zip')"></span>
              </label>

            </div>
          </div>

          <label :class="{'is-invalid-label': editForm.errors.has('profile.website')}">Website
            <input type="text" v-model="editForm.profile.website" :class="{'is-invalid-input': editForm.errors.has('profile.website')}"></textarea>
            <span :class="['form-error', {'is-visible': editForm.errors.has('profile.website')}]" v-text="editForm.errors.get('profile.website')"></span>
          </label>

          <label :class="{'is-invalid-label': editForm.errors.has('profile.email')}">Email
            <input type="email" v-model="editForm.profile.email" :class="{'is-invalid-input': editForm.errors.has('profile.email')}"></textarea>
            <span :class="['form-error', {'is-visible': editForm.errors.has('profile.email')}]" v-text="editForm.errors.get('profile.email')"></span>
          </label>

          <label :class="{'is-invalid-label': editForm.errors.has('profile.phone')}">Phone
            <input type="text" v-model="editForm.profile.phone" :class="{'is-invalid-input': editForm.errors.has('profile.phone')}"></textarea>
            <span :class="['form-error', {'is-visible': editForm.errors.has('profile.phone')}]" v-text="editForm.errors.get('profile.phone')"></span>
          </label>

          <div>
            <label for="place-edit-tags" :class="{'is-invalid-label': editForm.errors.has('tags')}">Tags</label>
            <div id="place-edit-tags" class="tags-field">
              <template v-for="tag in tags">
                <input type="checkbox" :value="tag.id" v-model="editForm.tags" :id="'edit-place-tag-' + tag.id">
                <label class="button hollow tiny" :key="tag.id" :for="'edit-place-tag-' + tag.id">{{ tag.name }}</label>
              </template>
            </div>
            <span :class="['form-error', {'is-visible': editForm.errors.has('tags')}]" v-text="editForm.errors.get('tags')"></span>
          </div>

        </div>
        <div class="modal-foot">
          <div class="modal-foot-right">
            <button type="button"class="button secondary"
              @click="editModal = false"
              :disabled="editForm.isSubmitting()">
              Cancel
            </button>
            <button class="button loading"
              :disabled="editForm.isSubmitting()">
              Save Changes
            </button>
          </div>
        </div>
      </form>
    </modal>

  </div>
</template>

<script>
import Modal from '../Modal.vue'
import Form from '../../utilities/Form'
import { parseUrlHost } from '../../helpers'
import Events from '../events/Events.vue'
import Comments from '../comments/Comments.vue'
import SubscribeButton from '../SubscribeButton.vue'

export default {
  components: { Modal, Events, Comments, SubscribeButton },

  props: ['attributes'],

  data () {
    return {
      place: this.attributes,

      tags: false,

      editForm: new Form({
        id: null,
        name: '',
        start_time: null,
        end_time: null,
        place_id: null,
        place: { name: '', id: null },
        description: null,
        user_id: null,
        profile: {},
        tags: []
      }),

      editModal: false,
    }
  },

  methods: {
    parseUrlHost,

    /**
     * Get all places.
     */
    getPlace () {
      return axios.get('/api/places/' + this.place.id)
        .then(({data}) => {
          this.place = data
          return data
        })
    },

    /**
     * Get all tags.
     */
    getTags () {
      return axios.get('/api/tags')
        .then(({data}) => {
          this.tags = data
          return data
        })
    },

    /**
     * Create the given resource.
     */
    edit (place) {
      this.editForm.id = place.id
      this.editForm.name = place.name
      this.editForm.street = place.street
      this.editForm.city = place.city
      this.editForm.state = place.state
      this.editForm.zip = place.zip
      this.editForm.profile = place.profile
      this.editForm.tags = place.tags.map(tag => tag.id)

      this.getTags()

      this.editModal = true
    },

    /**
     * Store the given resource.
     */
    update () {
      this.editForm.put('/api/places/' + this.editForm.id)
        .then(place => {
          this.getPlace()
          this.editModal = false
          window.flash('Place updated.')
        })
        .catch(error => {
          this.editModal = error.response.status === 422
          window.flash('Updating place failed.', 'alert')
        })
    }
  }
}
</script>
