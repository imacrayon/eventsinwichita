<template>
  <div style="margin-bottom: 2rem;">

    <div class="head-bar">
      <div class="head-bar-container">
          <h1 class="head-bar-title">
            <svg class="icon icon-calendar"><use xlink:href="/images/icons.svg#icon-pin"></use></svg>
            Places
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
                    <place-filters></place-filters>
                </div>
              </dropdown>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <places ref="places"></places>

    <!-- Create Place Modal -->
    <modal v-if="createModal">
      <div class="modal-head">
        <div class="modal-title">Create Place</div>
      </div>
      <form @submit.prevent="store" @input="createForm.errors.clear($event.target.name)">
        <div class="modal-section">

          <label :class="{'is-invalid-label': createForm.errors.has('name')}">Name
            <input type="text" v-model="createForm.name" :class="{'is-invalid-input': createForm.errors.has('name')}"></textarea>
            <span :class="['form-error', {'is-visible': createForm.errors.has('name')}]" v-text="createForm.errors.get('name')"></span>
          </label>

          <label :class="{'is-invalid-label': createForm.errors.has('street')}">Street
            <input type="text" v-model="createForm.street" :class="{'is-invalid-input': createForm.errors.has('street')}"></textarea>
            <span :class="['form-error', {'is-visible': createForm.errors.has('street')}]" v-text="createForm.errors.get('street')"></span>
          </label>

          <div class="grid-x grid-margin-x">
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': createForm.errors.has('city')}">City
                <input type="text" v-model="createForm.city" :class="{'is-invalid-input': createForm.errors.has('city')}"></textarea>
                <span :class="['form-error', {'is-visible': createForm.errors.has('city')}]" v-text="createForm.errors.get('city')"></span>
              </label>

            </div>
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': createForm.errors.has('state')}">State
                <input type="text" v-model="createForm.state" :class="{'is-invalid-input': createForm.errors.has('state')}"></textarea>
                <span :class="['form-error', {'is-visible': createForm.errors.has('state')}]" v-text="createForm.errors.get('state')"></span>
              </label>

            </div>
            <div class="small-12 medium-4 cell">

              <label :class="{'is-invalid-label': createForm.errors.has('zip')}">Zip
                <input type="text" v-model="createForm.zip" :class="{'is-invalid-input': createForm.errors.has('zip')}"></textarea>
                <span :class="['form-error', {'is-visible': createForm.errors.has('zip')}]" v-text="createForm.errors.get('zip')"></span>
              </label>

            </div>
          </div>

          <label :class="{'is-invalid-label': createForm.errors.has('profile.website')}">Website
            <input type="text" v-model="createForm.profile.website" :class="{'is-invalid-input': createForm.errors.has('profile.website')}"></textarea>
            <span :class="['form-error', {'is-visible': createForm.errors.has('profile.website')}]" v-text="createForm.errors.get('profile.website')"></span>
          </label>


          <label :class="{'is-invalid-label': createForm.errors.has('profile.email')}">Email
            <input type="email" v-model="createForm.profile.email" :class="{'is-invalid-input': createForm.errors.has('profile.email')}"></textarea>
            <span :class="['form-error', {'is-visible': createForm.errors.has('profile.email')}]" v-text="createForm.errors.get('profile.email')"></span>
          </label>

          <label :class="{'is-invalid-label': createForm.errors.has('profile.phone')}">Phone
            <input type="text" v-model="createForm.profile.phone" :class="{'is-invalid-input': createForm.errors.has('profile.phone')}"></textarea>
            <span :class="['form-error', {'is-visible': createForm.errors.has('profile.phone')}]" v-text="createForm.errors.get('profile.phone')"></span>
          </label>

          <div>
            <label for="event-edit-tags" :class="{'is-invalid-label': createForm.errors.has('tags')}">Tags</label>
            <div id="event-edit-tags">
              <label v-for="tag in tags" :class="{'is-invalid-label': createForm.errors.has('tags')}">
                <input type="checkbox" :value="tag.id" v-model="createForm.tags" :class="{'is-invalid-input': createForm.errors.has('tags')}">
                {{ tag.name }}
              </label>
            </div>
            <span :class="['form-error', {'is-visible': createForm.errors.has('tags')}]" v-text="createForm.errors.get('tags')"></span>
          </div>

        </div>
        <div class="modal-foot">
          <div class="modal-foot-right">
            <button type="button"
              class="button secondary"
              @click="createModal = false"
              :disabled="editForm.isSubmitting()">
              Cancel
            </button>
            <button class="button loading"
              :disabled="createForm.isSubmitting()">
              Save Changes
            </button>
          </div>
        </div>
      </form>
    </modal>


    <!-- Edit Place Modal -->
    <modal v-if="editModal">
      <div class="modal-head">
        <div class="modal-title">Edit Place</div>
      </div>
      <form @submit.prevent="storePlace" @input="editForm.errors.clear($event.target.name)">
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
            <label for="event-edit-tags" :class="{'is-invalid-label': editForm.errors.has('tags')}">Tags</label>
            <div id="event-edit-tags">
              <label v-for="tag in tags" :class="{'is-invalid-label': editForm.errors.has('tags')}">
                <input type="checkbox" :value="tag.id" v-model="editForm.tags" :class="{'is-invalid-input': editForm.errors.has('tags')}">
                {{ tag.name }}
              </label>
            </div>
            <span :class="['form-error', {'is-visible': editForm.errors.has('tags')}]" v-text="editForm.errors.get('tags')"></span>
          </div>

        </div>
        <div class="modal-foot">
          <div class="modal-foot-right">
            <button type="button" class="button secondary"
              :disabled="editForm.isSubmitting()"
              @click="editModal = false">
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
import Places from '../places/Places.vue'
import PlaceFilters from '../places/PlaceFilters.vue'

export default {
  components: { Places, PlaceFilters, Modal },

  data () {
    return {
      tags: false,

      createForm: new Form({
        name: '',
        street: '',
        city: '',
        state: '',
        zip: '',
        tags: [],
        profile: {}
      }),

      createModal: false,

      editForm: new Form({
        name: '',
        street: '',
        city: '',
        state: '',
        zip: '',
        tags: [],
        profile: {}
      }),

      editModal: false
    }
  },

  methods: {
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
    create (place) {
      this.getTags()

      this.editModal = true
    },

    /**
     * Store the given resource.
     */
    store () {
      this.editForm.post('/api/places')
        .then(place => {
          this.$refs.places.getPlaces()
          this.editModal = false
          window.flash('Place updated.')
        })
        .catch(error => {
          this.editModal = false
          window.flash('Updating place failed.', 'alert')
        })
    },

    /**
     * Edit the given resource.
     */
    edit (place) {
      this.editForm.name = place.name
      this.editForm.street = place.street
      this.editForm.city = place.city
      this.editForm.state = place.state
      this.editForm.zip = place.zip
      this.editForm.tags = place.tags.map(tag => tag.id)
      this.editForm.profile = place.profile

      this.getTags()

      this.editModal = true
    },

    /**
     * Update the resource being edited.
     */
    update () {
      this.editForm.put('/api/places/' + this.editForm.id)
        .then(response => {
          this.getPlaces()
          this.editModal = false
          window.flash('Place updated.')
        })
        .catch(error => {
          this.editModal = false
          window.flash('Updating place failed.', 'alert')
        })
    }
  }
}
</script>
