<template>
  <div>

    <div class="callout alert" v-if="form.errors.has('facebook_id')">
      This event already exists.
    </div>

    <label :class="{'is-invalid-label': collector.errors.has('url')}">Facebook URL
      <input type="text" v-model="collector.url" :class="{'is-invalid-input': collector.errors.has('url')}" @input="fetchEvent">
      <span :class="['collector-error', {'is-visible': collector.errors.has('url')}]" v-text="collector.errors.get('url')"></span>
    </label>
    <p class="help-text">We'll copy your event from Facebook for you.</p>

    <label :class="{'is-invalid-label': form.errors.has('name')}">Name
      <input type="text" v-model="form.name" :class="{'is-invalid-input': form.errors.has('name')}" required>
      <span :class="['form-error', {'is-visible': form.errors.has('name')}]" v-text="form.errors.get('name')"></span>
    </label>

    <div class="grid-x grid-margin-x">
      <div class="small-12 medium-6 cell">

        <label :class="{'is-invalid-label': form.errors.has('start_time')}">Start
          <date-picker
            v-model="form.start_time"
            :class="{'is-invalid-input': form.errors.has('start_time')}"
          ></date-picker>
          <span :class="['form-error', {'is-visible': form.errors.has('start_time')}]" v-text="form.errors.get('start_time')"></span>
        </label>

      </div>
      <div class="small-12 medium-6 cell">

        <label :class="{'is-invalid-label': form.errors.has('end_time')}">End
          <date-picker
            v-model="form.end_time"
            :class="{'is-invalid-input': form.errors.has('end_time')}"
          ></date-picker>
          <span :class="['form-error', {'is-visible': form.errors.has('end_time')}]" v-text="form.errors.get('end_time')"></span>
        </label>

      </div>
    </div>

    <label :class="{'is-invalid-label': form.errors.has('place_id')}">Place
      <place-picker v-model="form.place" :source="places" :class="{'is-invalid-input': form.errors.has('place_id')}"></place-picker>
      <span :class="['form-error', {'is-visible': form.errors.has('place_id')}]" v-text="form.errors.get('place_id')"></span>
    </label>

    <label :class="{'is-invalid-label': form.errors.has('description')}">Description
      <textarea v-model="form.description" :class="{'is-invalid-input': form.errors.has('description')}"></textarea>
      <span :class="['form-error', {'is-visible': form.errors.has('description')}]" v-text="form.errors.get('description')"></span>
    </label>

    <div>
      <label for="event-create-tags" :class="{'is-invalid-label': form.errors.has('tags')}">Tags</label>
      <div id="event-create-tags" class="tags-field">
        <template v-for="tag in tags">
          <input type="checkbox" :value="tag.id" v-model="form.tags" :id="'edit-event-tag-' + tag.id">
          <label class="button hollow tiny" :key="tag.id" :for="'edit-event-tag-' + tag.id">{{ tag.name }}</label>
        </template>
      </div>
      <span :class="['form-error', {'is-visible': form.errors.has('tags')}]" v-text="form.errors.get('tags')"></span>
    </div>

  </div>
</template>

<script>
import moment from 'moment'
import debounce from 'lodash.debounce'
import Form from '../../utilities/Form'
import DatePicker from '../DatePicker.vue'
import PlacePicker from '../PlacePicker.vue'
import { formatUrlDate } from '../../helpers'

export default {
  props: ['form'],

  components: { DatePicker, PlacePicker },

  data () {
    return {
      places: false,

      tags: false,

      collector: new Form({
        url: null,
        event_id: null
      }),
    }
  },

  created () {
    this.getPlaces()
    this.getTags()
  },

  methods: {
    getPlaces () {
      return axios.get('/api/places')
        .then(({data}) => {
          this.places = data
          return data
        })
    },

    getTags () {
      return axios.get('/api/tags')
        .then(({data}) => {
          this.tags = data
          return data
        })
    },

    fetchEvent: debounce(function (e) {
      const url = e.target.value
      if (url.indexOf('facebook.com') > -1) {
        this.collector.event_id = url.split('/')[4]
        if (this.collector.event_id) {
          this.collector.get(`https://graph.facebook.com/v2.10/${this.collector.event_id}?access_token=${window.App.facebook_token}`)
            .then(event => {
              // Persist the URL after the collector resets
              this.collector.url = url

              this.form.name = event.name
              this.form.start_time = formatUrlDate(moment(event.start_time))
              this.form.end_time = event.end_time
                ? formatUrlDate(moment(event.end_time))
                : moment(event.start_time).endOf('day')
              this.form.description = event.description
              this.form.facebook_id = event.id

              if (event.place && event.place.id) {
                this.form.place = {
                  name: event.place.name,
                  facebook_id: event.place.id
                }
                // TODO: This is lame. Find a cleaner way to programmatically
                // set a value for the PlacePicker.
                Vue.nextTick(() => {
                  document.getElementById('place_id').dispatchEvent(new Event('keyup'))
                })
              }
              window.flash(`Event info fetched.`)
            })
            .catch(error => {
              window.flash(`We couldn't grab any info from that link. The event might be private or our website could just suck.`, 'alert')
            })
        }
      }
    }, 500)
  }
}
</script>
