const ENDPOINT_GET = '/admin/departments',
      ENDPOINT_ALL = '/admin/departments/all',
      ENDPOINT_FETCH = '/admin/departments/{id}',
      ENDPOINT_CREATE = '/admin/departments/create',
      ENDPOINT_SEARCH = '/admin/departments/search',
      ENDPOINT_PERMISSIONS = '/admin/departments/{id}/permissions'

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

  setPermissions (id, ids = []) {
    let url = ENDPOINT_PERMISSIONS.formatUnicorn({ id })

    return this.$http.put(url, { ids })
  }

}