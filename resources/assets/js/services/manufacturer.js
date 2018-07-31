const ENDPOINT_GET = '/admin/manufacturers',
      ENDPOINT_FETCH = '/admin/manufacturers/{id}',
      ENDPOINT_CREATE = '/admin/manufacturers/create',
      ENDPOINT_SEARCH = '/admin/manufacturers/search'

export default class {

  constructor (app) {
    this.$http = app.$http
  }

  get (params) {
    return this.$http.get(ENDPOINT_GET, { params })
  }

  fetch (id, params) {
    let url = ENDPOINT_FETCH.formatUnicorn({ id })

    return this.$http.get(url, { params })
  }

  search (params) {
    return this.$http.get(ENDPOINT_SEARCH, { params })
  }

  create (data) {
    return this.$http.post(ENDPOINT_CREATE, data)
  }

  update (data) {
    let id = data.id, url = ENDPOINT_FETCH.formatUnicorn({ id })

    return this.$http.patch(url, data)
  }

  delete (id) {
    let url = ENDPOINT_FETCH.formatUnicorn({ id })
    
    return this.$http.delete(url)
  }

}