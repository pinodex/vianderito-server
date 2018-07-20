const ENDPOINT_GET = '/dashboard/identity/users/index.json',
      ENDPOINT_ALL = '/dashboard/identity/users/all.json',
      ENDPOINT_FETCH = '/dashboard/identity/users/{id}',
      ENDPOINT_CREATE = '/dashboard/identity/users/create',
      ENDPOINT_SEARCH = '/dashboard/identity/users/search'

export default class {

  constructor (app) {
    this.$http = app.$http
  }

  get (params) {
    return this.$http.get(ENDPOINT_GET, { params })
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