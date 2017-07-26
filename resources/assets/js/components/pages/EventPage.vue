<template>
  <div>

    <div class="head-bar">
      <div class="head-bar-container">
          <h1 class="head-bar-title">{{ event.name }}</h1>
      </div>
    </div>

    <div class="top-bar">
      <div class="top-bar-container">
        <div class="top-bar-right" v-if="authorize(user => user.id)">
          <ul class="menu">
            <li v-if="authorize(user => event.user_id === user.id)">
              <a href="#" @click="edit(event)">
                <svg class="icon"><use xlink:href="/images/icons.svg#icon-pencil"></use></svg>
                <span>Edit</span>
              </a>
            </li>
            <li>
              <subscribe-button :active="event.is_subscribed_to"></subscribe-button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="grid-container grid-container-padded">
      <div class="grid-x grid-margin-x">
        <div class="cell large-8 small-order-2 large-order-1">

          <div class="page-section">
            <h2 class="page-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-list"></use></svg>
              Description
            </h2>
            {{ event.description }}
          </div>

          <div class="page-section">
            <h2 class="page-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-comment"></use></svg>
              Comments
            </h2>
            <comments :comments="event.comments" :admin="event.user_id"></comments>
          </div>

        </div>
        <div class="cell large-4 small-order-1 large-order-2">

          <div class="page-section">
            <h2 class="page-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-clock"></use></svg>
              Time
            </h2>
            <span v-html="displayTime"></span>
          </div>

          <div class="page-section">
            <h2 class="page-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-pin"></use></svg>
              Location
            </h2>
            <a :href="`/places/${event.place.id}`">{{ event.place.name }}</a>
          </div>

          <div class="page-section">
            <h2 class="page-section-title">
              <svg class="icon"><use xlink:href="/images/icons.svg#icon-user"></use></svg>
              Posted By
            </h2>
            <a :href="'/users/' + event.user.id">
              {{ event.user.name }}
            </a>
          </div>

          <div class="page-section" v-if="event.tags.length > 0">
              <h2 class="page-section-title">
                <svg class="icon"><use xlink:href="/images/icons.svg#icon-tag"></use></svg>
                Tags
              </h2>
              <p class="event-tags">
                <span v-for="tag in event.tags" class="label">{{ tag.name }}</span>
              </p>
          </div>

        </div>
      </div>
    </div>

    <div class="call-to-action text-center">
      <div class="grid-container grid-container-padded">
          <svg class="icon" style="font-size: 3rem;"><use xlink:href="/images/icons.svg#icon-eye"></use></svg>
          <h3>Keep an Eye on Things</h3>
          <div v-if="!authorize(user => user.id)">
            <p>
              Login to get notifications when this event is updated.
            </p>
            <a class="button hollow white" href="/login">Login</a>
            <a class="button hollow white" href="/register">Create An Account</a>
          </div>
          <div v-else>
            <p>
              Click the <strong>Watch</strong> button at the top of the page. To get notifications when this event is updated.
            </p>
          </div>
      </div>
    </div>

    <!-- Edit Event Modal -->
    <modal v-if="editModal">
      <div class="modal-head">
        <div class="modal-title">Edit Event</div>
      </div>
      <form @submit.prevent="update" @input="editForm.errors.clear($event.target.name)">
        <div class="modal-section">

          <label :class="{'is-invalid-label': editForm.errors.has('name')}">Name
            <input type="text" v-model="editForm.name" :class="{'is-invalid-input': editForm.errors.has('name')}"></textarea>
            <span :class="['form-error', {'is-visible': editForm.errors.has('name')}]" v-text="editForm.errors.get('name')"></span>
          </label>

          <div class="grid-x grid-margin-x">
            <div class="small-12 medium-6 cell">

              <label :class="{'is-invalid-label': editForm.errors.has('start_time')}">Start
                <date-picker type="text" :options="startTimeOptions" v-model="editForm.start_time" :class="{'is-invalid-input': editForm.errors.has('start_time')}"></date-picker>
                <span :class="['form-error', {'is-visible': editForm.errors.has('start_time')}]" v-text="editForm.errors.get('start_time')"></span>
              </label>

            </div>
            <div class="small-12 medium-6 cell">

              <label :class="{'is-invalid-label': editForm.errors.has('end_time')}">End
                <date-picker type="text" :options="endTimeOptions" v-model="editForm.end_time" :class="{'is-invalid-input': editForm.errors.has('end_time')}"></date-picker>
                <span :class="['form-error', {'is-visible': editForm.errors.has('end_time')}]" v-text="editForm.errors.get('end_time')"></span>
              </label>

            </div>
          </div>

          <label :class="{'is-invalid-label': editForm.errors.has('place_id')}">Place
            <place-picker v-model="editForm.place" :source="places" :class="{'is-invalid-input': editForm.errors.has('place_id')}"></place-picker>
            <span :class="['form-error', {'is-visible': editForm.errors.has('place_id')}]" v-text="editForm.errors.get('place_id')"></span>
          </label>

          <label :class="{'is-invalid-label': editForm.errors.has('description')}">Description
            <textarea v-model="editForm.description" :class="{'is-invalid-input': editForm.errors.has('description')}"></textarea>
            <span :class="['form-error', {'is-visible': editForm.errors.has('description')}]" v-text="editForm.errors.get('description')"></span>
          </label>

          <div>
            <label for="event-edit-tags" :class="{'is-invalid-label': editForm.errors.has('tags')}">Tags</label>
            <div id="event-edit-tags" class="tags-field">
              <template v-for="tag in tags">
                <input type="checkbox" :value="tag.id" v-model="editForm.tags" :id="'edit-event-tag-' + tag.id">
                <label class="button hollow tiny" :key="tag.id" :for="'edit-event-tag-' + tag.id">{{ tag.name }}</label>
              </template>
            </div>
            <span :class="['form-error', {'is-visible': editForm.errors.has('tags')}]" v-text="editForm.errors.get('tags')"></span>
          </div>

        </div>
        <div class="modal-foot">
          <div class="modal-foot-right">
            <button type="button" class="button secondary"
              @click="editModal = false"
              :disabled="editForm.isSubmitting()">
              Cancel
            </button>
            <button class="button loading"
              :disabled="editForm.isSubmitting()">
              Save Changes
            </button>
          </div>
          <div class="modal-foot-left">
            <button type="button" class="button alert hollow"
              @click="confirmDestroy"
              :disabled="editForm.isSubmitting()">
              Destroy
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
              :disabled="editForm.isSubmitting()">
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

    <!-- Destroy event confirmation -->
    <modal v-if="confirmDestroyModal">
      <div class="modal-head">
        <div class="modal-title">Confirm Destruction</div>
      </div>
      <div class="modal-section">
        <p>Are you super sure you want to destroy <strong>{{ editForm.name }}</strong>?</p>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-right">
          <button class="button transparent"
            @click="confirmDestroyModal = false"
            :disabled="editForm.isSubmitting()">
            Keep
          </button>
          <button class="button loading"
            @click="destroy"
            :disabled="editForm.isSubmitting()">
            Destroy
          </button>
        </div>
      </div>
    </modal>

  </div>
</template>

<script>
import moment from 'moment'
import Modal from '../Modal.vue'
import Form from '../../utilities/Form'
import DatePicker from '../DatePicker.vue'
import PlacePicker from '../PlacePicker.vue'
import Comments from '../comments/Comments.vue'
import CommentsForm from '../comments/CommentsForm.vue'
import SubscribeButton from '../SubscribeButton.vue'

export default {
  components: { Modal, DatePicker, PlacePicker, Comments, CommentsForm, SubscribeButton },

  props: ['attributes'],

  data () {
    return {
      event: this.attributes,

      places: false,

      tags: false,

      editForm: new Form({
        id: null,
        name: '',
        start_time: null,
        end_time: null,
        place_id: null,
        place: { name: '', id: null },
        description: null,
        tags: []
      }),

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

      startTimeOptions: {
        enableTime: true,
        minDate: moment().startOf('day').toDate(),
        dateFormat: 'Y-m-d H:i:S',
        altInput: true,
        altFormat: 'F j, Y h:i K'
      },

      confirmDestroyModal: false
    }
  },

  computed: {
    displayTime () {
      let start = moment(this.event.start_time)
      let end = moment(this.event.end_time)
      if (start.isSame(end, 'day')) {
        return `${start.format('dddd, MMMM D [at] h:mm A')} &ndash; ${end.format('h:mm A')}`
      }

      return `${start.format('dddd, MMMM D [at] h:mm A')} &ndash; ${end.format('h:mm A [on] dddd, MMM D')}`
    },

    endTimeOptions () {
      return {
        enableTime: true,
        minDate: moment(this.editForm.start_time).startOf('day').toDate(),
        dateFormat: 'Y-m-d H:i:S',
        altInput: true,
        altFormat: 'F j, Y h:i K'
      }
    }
  },

  methods: {
    /**
     * Get event.
     */
    getEvent () {
      return axios.get(`/api/events/${this.event.id}`)
        .then(({data}) => {
          this.event = data
          return data
        })
    },

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

          this.editForm.place = place
          this.update()
        })
        .catch(error => {
          this.createPlaceModal = false
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
     * Edit the given resource.
     */
    edit (event) {
      this.editForm.id = event.id
      this.editForm.name = event.name
      this.editForm.start_time = moment(event.start_time).format('YYYY-MM-DD HH:mm:ss')
      this.editForm.end_time = moment(event.end_time).format('YYYY-MM-DD HH:mm:ss')
      this.editForm.place = event.place
      this.editForm.description = event.description
      this.editForm.tags = event.tags.map(tag => tag.id)

      this.getPlaces()

      this.getTags()

      this.editModal = true
    },

    /**
     * Update the resource being edited.
     */
    update () {
      if (!this.editForm.place.id) {
        this.definePlace(this.editForm)
      } else {
        this.editForm.place_id = this.editForm.place.id
        this.editForm.put(`/api/events/${this.editForm.id}`)
          .then(data => {
            this.getEvent()
            this.editModal = false
            window.flash('Event updated.')
          })
          .catch(error => {
            this.editModal = false
            window.flash('Updating event failed.', 'alert')
          })
      }
    },

    confirmDestroy () {
      this.confirmDestroyModal = true
    },

    destroy  () {
      this.editForm.delete(`/api/events/${this.editForm.id}`)
        .then(data => {
          this.confirmDestroyModal = false
          this.editModal = false
          window.location = `/users/${window.App.user.id}/events`
        })
    }

  }
}
</script>
