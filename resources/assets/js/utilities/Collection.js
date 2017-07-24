export default class {

  constructor (items) {
    this.items = items || {}
  }

  /**
   * Set items.
   *
   * @param  {Object} items items
   */
  set (items) {
    this.items = items
  }

  /**
   * Get an item.
   *
   * @param  {String} key The key.
   * @return {String} The item message.
   */
  get (key) {
    if (this.has(key)) {
      return this.items[key][0]
    }
  }

  /**
   * Determine if a item exists.
   *
   * @param  {String} key The key item key.
   * @return {String} The item message.
   */
  has (key) {
    return this.items.hasOwnProperty(key)
  }

  /**
   * Clear a particular key or all items.
   *
   * @param  {String|Null} key The key item key.
   */
  clear (key) {
    if (key) {
      delete this.items[key]
      return
    }
    this.items = {}
  }

  /**
   * Determine if any items exists.
   */
  any () {
    return Object.keys(this.items).length > 0
  }

}
