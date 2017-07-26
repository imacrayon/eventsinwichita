<template>
  <div>

    <div class="head-bar">
      <div class="head-bar-container">
          <h1 class="head-bar-title">
            <svg class="icon icon-calendar"><use xlink:href="/images/icons.svg#icon-calendar"></use></svg>
            Events
          </h1>
      </div>
    </div>

    <div class="top-bar">
      <div class="top-bar-container">
        <div class="top-bar-right">
          <ul class="menu">
            <li>
              <dropdown>
                <a class="dropdown">
                  <svg class="icon"><use xlink:href="/images/icons.svg#icon-funnel"></use></svg>
                  Filter
                </a>
                <div slot="menu">
                    <event-filters></event-filters>
                </div>
              </dropdown>
            </li>
            <li v-if="authorize(user => user.id)">
              <a @click="create">
                <svg class="icon"><use xlink:href="/images/icons.svg#icon-plus"></use></svg>
                Add Event
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <events ref="events"></events>

    <div class="call-to-action text-center">
      <div class="grid-container grid-container-padded">
          <svg class="icon" style="font-size: 3rem;margin-bottom: .5rem;"><use xlink:href="/images/icons.svg#icon-calendar"></use></svg>
          <h3>Add Your Own Event</h3>
          <div v-if="!authorize(user => user.id)">
            <p>
              Login to add your own event. <em>(It's free!)</em>
            </p>
            <a class="button hollow white" href="/login">Login</a>
            <a class="button hollow white" href="/register">Create An Account</a>
          </div>
          <div v-else>
            <button class="button hollow white" @click="create">
              <svg class="icon" style="top: -.1em;"><use xlink:href="/images/icons.svg#icon-plus"></use></svg>
              Add Event
            </button>
          </div>
      </div>
    </div>

    <!-- Create Event Modal -->
    <modal v-if="createModal">
      <div class="modal-head">
        <div class="modal-title">New Event</div>
      </div>
      <form @submit.prevent="store" @input="createForm.errors.clear($event.target.name)">
        <div class="modal-section">


          <label :class="{'is-invalid-label': createForm.errors.has('name')}">Name
            <input type="text" v-model="createForm.name" :class="{'is-invalid-input': createForm.errors.has('name')}" required>
            <span :class="['form-error', {'is-visible': createForm.errors.has('name')}]" v-text="createForm.errors.get('name')"></span>
          </label>

          <div class="grid-x grid-margin-x">
            <div class="small-12 medium-6 cell">

              <label :class="{'is-invalid-label': createForm.errors.has('start_time')}">Start
                 <date-picker
                  v-model="createForm.start_time"
                  :class="{'is-invalid-input': createForm.errors.has('start_time')}"
                ></date-picker>
                <span :class="['form-error', {'is-visible': createForm.errors.has('start_time')}]" v-text="createForm.errors.get('start_time')"></span>
              </label>

            </div>
            <div class="small-12 medium-6 cell">

              <label :class="{'is-invalid-label': createForm.errors.has('end_time')}">End
                <date-picker
                  v-model="createForm.end_time"
                  :class="{'is-invalid-input': createForm.errors.has('end_time')}"
                ></date-picker>
                <span :class="['form-error', {'is-visible': createForm.errors.has('end_time')}]" v-text="createForm.errors.get('end_time')"></span>
              </label>

            </div>
          </div>

          <label :class="{'is-invalid-label': createForm.errors.has('place_id')}">Place
            <place-picker v-model="createForm.place" :source="places" :class="{'is-invalid-input': createForm.errors.has('place_id')}"></place-picker>
            <span :class="['form-error', {'is-visible': createForm.errors.has('place_id')}]" v-text="createForm.errors.get('place_id')"></span>
          </label>

          <label :class="{'is-invalid-label': createForm.errors.has('description')}">Description
            <textarea v-model="createForm.description" :class="{'is-invalid-input': createForm.errors.has('description')}"></textarea>
            <span :class="['form-error', {'is-visible': createForm.errors.has('description')}]" v-text="createForm.errors.get('description')"></span>
          </label>

          <div>
            <label for="event-edit-tags" :class="{'is-invalid-label': createForm.errors.has('tags')}">Tags</label>
            <div id="event-edit-tags" class="tags-field">
              <template v-for="tag in tags">
                <input type="checkbox" :value="tag.id" v-model="createForm.tags" :id="'edit-event-tag-' + tag.id">
                <label class="button hollow tiny" :key="tag.id" :for="'edit-event-tag-' + tag.id">{{ tag.name }}</label>
              </template>
            </div>
            <span :class="['form-error', {'is-visible': createForm.errors.has('tags')}]" v-text="createForm.errors.get('tags')"></span>
          </div>

        </div>
        <div class="modal-foot">
          <div class="modal-foot-right">
            <button type="button" class="button secondary"
              @click="createModal = false"
              :disabled="createForm.isSubmitting()">
              Cancel
            </button>
            <button class="button loading"
              :disabled="createForm.isSubmitting()">
              Save Event
            </button>
          </div>
        </div>
      </form>
    </modal>

    <!-- Add Place Modal -->
    <modal v-if="createPlaceModal">
      <div class="modal-head">
        <div class="modal-title">Add Place Information</div>
      </div>
      <form @submit.prevent="storePlace" @input="createPlaceForm.errors.clear($event.target.name)">
        <div class="modal-section">

          <div class="callout warning">
            You provided a location that's not in our database. Please tell us a little more about this place.
          </div>

          <label :class="{'is-invalid-label': createPlaceForm.errors.has('name')}">Name
            <input type="text" v-model="createPlaceForm.name" :class="{'is-invalid-input': createPlaceForm.errors.has('name')}"></textarea>
            <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('name')}]" v-text="createPlaceForm.errors.get('name')"></span>
          </label>

          <label :class="{'is-invalid-label': createPlaceForm.errors.has('street')}">Street
            <input type="text" v-model="createPlaceForm.street" :class="{'is-invalid-input': createPlaceForm.errors.has('street')}"></textarea>
            <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('street')}]" v-text="createPlaceForm.errors.get('street')"></span>
          </label>

          <div class="grid-x grid-margin-x">
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': createPlaceForm.errors.has('city')}">City
                <input type="text" v-model="createPlaceForm.city" :class="{'is-invalid-input': createPlaceForm.errors.has('city')}"></textarea>
                <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('city')}]" v-text="createPlaceForm.errors.get('city')"></span>
              </label>

            </div>
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': createPlaceForm.errors.has('state')}">State
                <input type="text" v-model="createPlaceForm.state" :class="{'is-invalid-input': createPlaceForm.errors.has('state')}"></textarea>
                <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('state')}]" v-text="createPlaceForm.errors.get('state')"></span>
              </label>

            </div>
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': createPlaceForm.errors.has('zip')}">Zip
                <input type="text" v-model="createPlaceForm.zip" :class="{'is-invalid-input': createPlaceForm.errors.has('zip')}"></textarea>
                <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('zip')}]" v-text="createPlaceForm.errors.get('zip')"></span>
              </label>

            </div>
          </div>

          <label :class="{'is-invalid-label': createPlaceForm.errors.has('profile.website')}">Website
            <input type="text" v-model="createPlaceForm.profile.website" :class="{'is-invalid-input': createPlaceForm.errors.has('profile.website')}"></textarea>
            <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('profile.website')}]" v-text="createPlaceForm.errors.get('profile.website')"></span>
          </label>

          <label :class="{'is-invalid-label': createPlaceForm.errors.has('profile.email')}">Email
            <input type="email" v-model="createPlaceForm.profile.email" :class="{'is-invalid-input': createPlaceForm.errors.has('profile.email')}"></textarea>
            <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('profile.email')}]" v-text="createPlaceForm.errors.get('profile.email')"></span>
          </label>

          <label :class="{'is-invalid-label': createPlaceForm.errors.has('profile.phone')}">Phone
            <input type="text" v-model="createPlaceForm.profile.phone" :class="{'is-invalid-input': createPlaceForm.errors.has('profile.phone')}"></textarea>
            <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('profile.phone')}]" v-text="createPlaceForm.errors.get('profile.phone')"></span>
          </label>

          <div>
            <label for="event-edit-tags" :class="{'is-invalid-label': createPlaceForm.errors.has('tags')}">Tags</label>
            <div id="event-edit-tags" class="tags-field">
              <template v-for="tag in tags">
                <input type="checkbox" :value="tag.id" v-model="createPlaceForm.tags" :id="'edit-place-tag-' + tag.id">
                <label class="button hollow tiny" :key="tag.id" :for="'edit-place-tag-' + tag.id">{{ tag.name }}</label>
              </template>
            </div>
            <span :class="['form-error', {'is-visible': createPlaceForm.errors.has('tags')}]" v-text="createPlaceForm.errors.get('tags')"></span>
          </div>

        </div>
        <div class="modal-foot">
          <div class="modal-foot-right">
            <button type="button" class="button secondary"
              @click="createPlaceModal = false"
              :disabled="createPlaceForm.isSubmitting()">
              Cancel
            </button>
            <button class="button loading"
              :disabled="createPlaceForm.isSubmitting()">
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
import Events from '../events/Events.vue'
import DatePicker from '../DatePicker.vue'
import EventFilters from '../events/EventFilters.vue'
import PlacePicker from '../PlacePicker.vue'
import { serialize } from '../../helpers'

export default {
  components: { Events, Modal, DatePicker, PlacePicker, EventFilters },

  data () {
    return {
      events: false,

      places: false,

      tags: false,

      createForm: new Form({
        id: null,
        name: '',
        start_time: undefined,
        end_time: undefined,
        place_id: null,
        place: { name: '', id: null },
        description: null,
        tags: []
      }),

      createModal: false,

      editModal: false,

      createPlaceForm: new Form({
        name: '',
        street: '',
        city: '',
        state: '',
        zip: '',
        profile: {},
        tags: []
      }),

      createPlaceModal: false,
    }
  },

  methods: {
    /**
     * Get all places.
     */
    getPlaces () {
      return axios.get('/api/places')
        .then(({data}) => {
          this.places = data
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

    geocode(place) {
      return axios.post('/api/geocode', place)
        .then(({ data }) => {
          return data
        })
        .catch(error => {
          throw error
        })
    },

    /**
     * Create the given resource.
     */
    createPlace (place) {
      this.createPlaceForm.name = place.name
      this.createPlaceForm.street = place.street
      this.createPlaceForm.city = place.city
      this.createPlaceForm.state = place.state
      this.createPlaceForm.zip = place.zip
      this.createPlaceForm.profile = place.profile
      this.createPlaceForm.tags = place.tags.map(tag => tag.id)

      this.getTags()

      this.createPlaceModal = true
    },

    /**
     * Store the given resource.
     */
    storePlace () {
      this.createPlaceForm.post('/api/places')
        .then(place => {
          this.getPlaces()
          this.createPlaceModal = false
          window.flash('Place updated.')

          this.createForm.place = place
          this.store()
        })
        .catch(error => {
          this.createPlaceModal = error.response.status === 422
          window.flash('Updating place failed.', 'alert')
        })
    },

    /**
     * Use form data to create or find an existing place.
     */
    definePlace(form) {
      this.geocode(form.place)
        .then(place => {
          this.createPlace(place)
        })
        .catch(error => {
          form.onFail({
            response: {
              status: 422,
              data: {place_id: ['Place could not be located.']}
            }
          })
        })
    },

    /**
     * Create the given resource.
     */
    create () {
      this.getPlaces()
      this.getTags()
      this.createModal = true
    },

    /**
     * Store the resource being edited.
     */
    store () {
      if (!this.createForm.place.id) {
        this.definePlace(this.createForm)
      } else {
        this.createForm.place_id = this.createForm.place.id
        this.createForm.post('/api/events')
          .then(data => {
            this.$refs.events.getEvents()
            this.createModal = false
            window.flash('Event created.')
          })
          .catch(error => {
            this.createModal = error.response.status === 422
            window.flash('Creating event failed.', 'alert')
          })
      }
    }
  }
}
</script>
