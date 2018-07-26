const ENDPOINT_GET = '/papi/admin/accounts',
      ENDPOINT_FETCH = '/papi/admin/accounts/{id}',
      ENDPOINT_AVATAR = '/papi/admin/accounts/{id}/avatar',
      ENDPOINT_CREATE = '/papi/admin/accounts/create',
      ENDPOINT_SEARCH = '/papi/admin/accounts/search'

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

  setAvatar (id, file) {
    let url = ENDPOINT_AVATAR.formatUnicorn({ id }),
        options = { headers: { 'Content-Type': 'multipart/form-data' }},
        data = new FormData()

        data.append('file', file)

    return this.$http.post(url, data, options)
  }

}