const ENDPOINT_GET = '/papi/admin/groups',
      ENDPOINT_ALL = '/papi/admin/groups/all',
      ENDPOINT_FETCH = '/papi/admin/groups/{id}',
      ENDPOINT_CREATE = '/papi/admin/groups/create',
      ENDPOINT_SEARCH = '/papi/admin/groups/search'

export default class {

  constructor (app) {
    this.$http = app.$http
  }

  get (params) {
    return this.$http.get(ENDPOINT_GET, { params })
  }

  all (params) {
    return this.$http.get(ENDPOINT_ALL, { params })
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