const ENDPOINT_GET = '/admin/products',
      ENDPOINT_FETCH = '/admin/products/{id}',
      ENDPOINT_PICTURE = '/admin/products/{id}/picture',
      ENDPOINT_CREATE = '/admin/products/create',
      ENDPOINT_SEARCH = '/admin/products/search'

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

  setPicture(id, file) {
    let url = ENDPOINT_PICTURE.formatUnicorn({ id }),
        options = { headers: { 'Content-Type': 'multipart/form-data' }},
        data = new FormData()

        data.append('file', file)

    return this.$http.post(url, data, options)
  }

}