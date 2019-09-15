import { DateTime } from 'luxon'

export const FORMAT_SHORT_DATE = 'n/j/Y'

export const FORMAT_TIME = 'h:i K'

export const FORMAT_SHORT_DATE_TIME = 'n/j/Y h:i K'

export const FORMAT_MYSQL = 'yyyy-MM-dd HH:mm:ss'

export function dateTokens(tokens) {
  const dictionary = {
    d: 'dd', // Day of the month, padded to 2
    D: 'EEE', // Mon through Sun
    l: 'EEEE', // Sunday through Saturday
    j: 'd', // Day of the month, no padding
    F: 'MMMM', // January through December
    m: 'MM', // month, padded to 2
    n: 'M', // month, no padding
    M: 'MMM', // Jan through Dec
    Y: 'yyyy', // Full year,
    H: 'HH', // Hour, 24 hour time
    h: 'h', // Hour, 12 hour time
    i: 'mm', // Minute
    S: 'ss', // Seconds,
    K: 'a', // AM, PM
  }

  return Array.from(tokens)
    .map(t => dictionary[t] || t)
    .join('')
}

export default {
  methods: {
    toAppTimezone(value, format = 'n/j/Y h:i K') {
      return value
        ? DateTime.fromFormat(value, dateTokens(format))
            .toUTC()
            .toFormat(FORMAT_MYSQL)
        : value
    },

    fromAppTimezone(value, format = 'n/j/Y h:i K') {
      if (!value) {
        return value
      }

      value = DateTime.fromISO(value, { zone: 'UTC' }).setZone(
        this.userTimezone
      )
      value = format ? value.toFormat(dateTokens(format)) : value

      return value
    },
  },

  computed: {
    userTimezone() {
      return 'America/Chicago' // DateTime.local().zoneName
    },
  },
}
