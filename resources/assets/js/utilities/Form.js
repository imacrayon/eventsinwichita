import Collection from './Collection'

export default class {

  constructor (data) {
    for (let field in data) {
      this[field] = data[field]
    }

    this.errors = new Collection()

    this._originalData = data

    this._submitting = false
  }

  /**
   * Determine if the form is in the middle of submitting data.
   */
  isSubmitting () {
    return this._submitting
  }

  /**
   * Determine if the form is submitting or caught up with errors.
   */
  busy () {
    return this.isSubmitting() || this.errors.any()
  }

  /**
   * Clear any state, field data, and errors.
   */
  reset () {
    for (let field in this._originalData) {
      this[field] = this._originalData[field]
    }
    this.errors.clear()
    this._submitting = false
  }

  /**
   * Get data as a FormData Object for uploading images and stuff.
   */
  getFormData () {
    let data = new window.FormData()
    for (let property in this._originalData) {
      data.append(property, this[property])
    }

    return data
  }

  /**
   * Get field data.
   */
  getData () {
    let data = {}
    for (let property in this._originalData) {
      data[property] = this[property]
    }

    return data
  }

  /**
   * Callback when data is successfully submitted.
   *
   * @param Object data Response data.
   */
  onSuccess (data) {
    this.reset()
  }

  /**
   * Callback when data submission fails.
   *
   * @param Object data Response data.
   */
  onFail (error) {
    this._submitting = false

    if (error.response) {
        this.errors.set(error.response.data)
    } else if (error.request) {
      window.error('There was no response from our server. Are you online?')
    } else {
      window.error('There was an error setting up a server request.')
    }
  }

  /**
   * Submit form data.
   *
   * @param  {String}  method Submission method.
   * @param  {String}  uri URI to submit to.
   * @param  {Object}  options Additional options to pass to HTTP client.
   * @param  {Object}  options Additional options to pass to HTTP client.
   * @return {Promise} HTTP client response.
   */
  submit (method, uri, options) {
    // Account for http methods that don't accept data
    const data = (method !== 'get' && method !== 'delete') ? this.getData() : options
    this._submitting = true
    return axios[method](uri, data, options)
      .then(({ data }) => {
        this.onSuccess(data)
        return data
      })
      .catch(error => {
        this.onFail(error)
        // Resolve promise if it's a validation error
        if (error.response.status === 422) return
        throw error
      })
  }

  /**
   * Helper for `GET` submissions.
   * @param  {String} uri The URI to submit to.
   * @param  {Object} options Additional options to pass to HTTP client.
   * @return {Promise} HTTP client response.
   */
  get (uri, options) {
    return this.submit('get', uri, options)
  }

  /**
   * Helper for `POST` submissions.
   * @param  {String} uri The URI to submit to.
   * @param  {Object} options Additional options to pass to HTTP client.
   * @return {Promise} HTTP client response.
   */
  post (uri, options) {
    return this.submit('post', uri, options)
  }

  /**
   * Helper for `PUT` submissions.
   * @param  {String} uri The URI to submit to.
   * @param  {Object} options Additional options to pass to HTTP client.
   * @return {Promise} HTTP client response.
   */
  put (uri, options) {
    return this.submit('put', uri, options)
  }

  /**
   * Helper for `PATCH` submissions.
   * @param  {String} uri The URI to submit to.
   * @param  {Object} options Additional options to pass to HTTP client.
   * @return {Promise} HTTP client response.
   */
  patch (uri, options) {
    return this.submit('patch', uri, options)
  }

  /**
   * Helper for `DELETE` submissions.
   * @param  {String} uri The URI to submit to.
   * @param  {Object} options Additional options to pass to HTTP client.
   * @return {Promise} HTTP client response.
   */
  delete (uri, options) {
    return this.submit('delete', uri, options)
  }

}
