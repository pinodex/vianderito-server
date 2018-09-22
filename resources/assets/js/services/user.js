const ENDPOINT_GET = '/admin/users',
      ENDPOINT_FETCH = '/admin/users/{id}',
      ENDPOINT_AVATAR = '/admin/users/{id}/avatar',
      ENDPOINT_RESTORE = '/admin/users/{id}/restore',
      ENDPOINT_DESTROY = '/admin/users/{id}/destroy',
      ENDPOINT_RESET = '/admin/users/{id}/reset_password',
      ENDPOINT_SEARCH = '/admin/users/search'

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

  update (data) {
    let id = data.id, url = ENDPOINT_FETCH.formatUnicorn({ id })

    return this.$http.patch(url, data)
  }

  delete (id) {
    let url = ENDPOINT_FETCH.formatUnicorn({ id })
    
    return this.$http.delete(url)
  }

   restore (id) {
    let url = ENDPOINT_RESTORE.formatUnicorn({ id })

    return this.$http.post(url)
  }

  destroy (id) {
    let url = ENDPOINT_DESTROY.formatUnicorn({ id })

    return this.$http.post(url)
  }

  resetPassword (id) {
    let url = ENDPOINT_RESET.formatUnicorn({ id })

    return this.$http.post(url)
  }

  setAvatar (id, file) {
    let url = ENDPOINT_AVATAR.formatUnicorn({ id }),
        options = { headers: { 'Content-Type': 'multipart/form-data' }},
        data = new FormData()

        data.append('file', file)

    return this.$http.post(url, data, options)
  }

}