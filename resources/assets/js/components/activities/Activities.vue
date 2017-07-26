<template>
  <div>
    <template v-for="(day, key) in calendar">
      <div class="sticky">
          <div class="sticky-fix calendar-day">
              <div class="grid-container grid-container-padded" v-html="key">
              </div>
          </div>
      </div>
      <div class="grid-container grid-container-padded">
        <div class="activity"
          v-for="activity in day"
          :key="activity.id"
        >
          <div class="activity-body" v-html="activity.description">
          </div>
          <div class="activity-meta">
            {{ diffForHumans(activity.created_at) }}
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import moment from 'moment'
import { urlMap, diffForHumans, serialize } from '../../helpers'

export default {
  props: {
    filters: {
      default () {
        return {
          limit: 50,
          user_id: ''
        }
      }
    }
  },

  data () {
    return {
      activities: false
    }
  },

  computed: {
    calendar () {
      if (this.activities === false) {
        return {}
      }
      return this.activities.reduce((activities, activity) => {
        activity.created_at = moment.utc(activity.created_at)
        activity.description = this.getDescription(activity)
        const date = (activity.created_at.isSame(moment(), 'day') ? 'Today &middot; ' : '')
          + activity.created_at.format('ddd MMM DD')
        activities[date] = activities[date] || []
        activities[date].push(activity)
        return activities
      }, {})
    }
  },

  created () {
    this.getActivities()
  },

  methods: {
    /**
     * Get all events.
     */
    getActivities () {
      return axios.get(`/api/activities?${serialize(this.filters)}`)
        .then(({data}) => {
          this.activities = data
          return data
        })
    },

    diffForHumans,

    getDescription (activity) {
      const resource = activity.name !== 'created_comment' ? activity.subject_type : activity.subject.subject_type
      const subject = activity.name !== 'created_comment' ? activity.subject : activity.subject.subject
      const map = {
        created_event: `Created <a href="${urlMap[resource]}${subject.id}">${subject.name}</a>`,
        updated_event: `Updated <a href="${urlMap[resource]}${subject.id}">${subject.name}</a>`,
        created_place: `Created <a href="${urlMap[resource]}${subject.id}">${subject.name}</a>`,
        updated_place: `Updated <a href="${urlMap[resource]}${subject.id}">${subject.name}</a>`,
        created_comment: `Commented on <a href="${urlMap[resource]}${subject.id}#comment-${activity.subject.id}">${subject.name}</a>`,
        created_user: `Joined Events in Wichita`
      }
      return map[activity.name]
    }
  }
}
</script>
