import moment from 'moment'

export function serialize (params, prefix) {
  const query = Object.keys(params).reduce((items, key) => {
    const value  = params[key];

    if (params.constructor === Array) {
      key = `${prefix}[]`
    } else if (params.constructor === Object) {
      key = (prefix ? `${prefix}[${key}]` : key)
    }

    if (typeof value === 'object') {
      const segment = serialize(value, key)
      if (segment.length) items.push(segment)
    } else if (value !== '') {
      items.push(`${key}=${encodeURIComponent(value)}`)
    }

    return items
  }, [])

  return [].concat.apply([], query).join('&')
}

export function setSearchParam(key, value)
{
    key = encodeURIComponent(key)
    value = encodeURIComponent(value)
    const search = document.location.search.substr(1)

    if (!search) {
      return [key, value].join('=')
    }

    let pairs = search.split('&')
    let i = pairs.length
    let pair
    while(i--) {
      pair = pairs[i].split('=');

      if (pair[0].replace(/%5B%5D/, '') === key) {
        pair[1] = value
        pairs[i] = pair.join('=');
        break;
      }
    }

    if (i < 0) {
      pairs[pairs.length] = [key, value].join('=')
    }

    return pairs.join('&');
}

export function getSearchParam(key, defaultVal)
{
    key = encodeURIComponent(key)

    let pairs = document.location.search.substr(1).split('&')

    let found = pairs.reduce((found, pair) => {
      pair = pair.split('=')
      if (pair[0].replace(/%5B%5D/, '') === key) {
        found.push(decodeURIComponent(pair[1]).replace(/\+/g, ' '))
      }
      return found
    }, [])

    if (found.length === 1) {
      return found[0] !== '' ? found[0] : defaultVal
    } else if (found.length === 0) {
      return defaultVal
    } else {
      return found
    }
}

export function parseUrlHost (url) {
  const urlParser = document.createElement('a')
  if (!url) return ''
  urlParser.href = url
  return urlParser.hostname
}

export function formatUrlDate (date) {
  return moment(date).format('YYYY-MM-DD HH:mm:ss')
}

export function diffForHumans (date) {
  return moment.utc(date).local().fromNow()
}

export const urlMap = {
  'App\\Event': '/events/',
  'App\\Place': '/places/'
}

