import { DateTime } from 'luxon'
import { FORMAT_MYSQL } from '@/mixins/InteractsWithDates'

export default {
  methods: {
    formatEventTimes() {
      const start = this.startTime
        .replace(/^\d{4}-\d{2}-\d{2}/, this.startDate.substring(0, 10))
        .substring(0, 19)
        .replace('T', ' ')
      console.log(start)
      let end = ''
      if (this.endTime) {
        end = this.endTime
          .replace(/^\d{4}-\d{2}-\d{2}/, this.startDate.substring(0, 10))
          .substring(0, 19)
          .replace('T', ' ')
        const startHour = Number(start.substring(11, 13))
        const endHour = Number(end.substring(11, 13))
        if (startHour > endHour) {
          end = DateTime.fromSQL(end, { zone: 'UTC' })
            .plus({ days: 1 })
            .toFormat(FORMAT_MYSQL)
        }
      }

      return { start, end }
    },
  },
}
