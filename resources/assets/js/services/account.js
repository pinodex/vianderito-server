const ENDPOINT_GET = '/admin/accounts',
      ENDPOINT_FETCH = '/admin/accounts/{id}',
      ENDPOINT_AVATAR = '/admin/accounts/{id}/avatar',
      ENDPOINT_RESET = '/admin/accounts/{id}/reset_password',
      ENDPOINT_LOGS = '/admin/accounts/{id}/logs',
      ENDPOINT_CREATE = '/admin/accounts/create',
      ENDPOINT_SEARCH = '/admin/accounts/search'

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

  getLogs (id, params = {}) {
    let url = ENDPOINT_LOGS.formatUnicorn({ id })

    return this.$http.get(url, { params })
  }

  setAvatar (id, file) {
    let url = ENDPOINT_AVATAR.formatUnicorn({ id }),
        options = { headers: { 'Content-Type': 'multipart/form-data' }},
        data = new FormData()

        data.append('file', file)

    return this.$http.post(url, data, options)
  }

  resetPassword (id) {
    let url = ENDPOINT_RESET.formatUnicorn({ id })

    return this.$http.post(url)
  }

}