const ENDPOINT_GET = '/admin/inventories',
      ENDPOINT_GET_ID = '/admin/inventories/byId',
      ENDPOINT_FETCH = '/admin/inventories/{id}',
      ENDPOINT_RESTORE = '/admin/inventories/{id}/restore',
      ENDPOINT_DESTROY = '/admin/inventories/{id}/destroy',
      ENDPOINT_LOSSES = '/admin/inventories/{id}/losses',
      ENDPOINT_LOSSES_SINGLE = '/admin/inventories/{id}/losses/{lid}',
      ENDPOINT_CREATE = '/admin/inventories/create',
      ENDPOINT_SEARCH = '/admin/inventories/search'

export default class {

  constructor (app) {
    this.$http = app.$http
  }

  get (params) {
    return this.$http.get(ENDPOINT_GET, { params })
  }

  getById (id, params = {}) {
    params.id = id

    return this.$http.get(ENDPOINT_GET_ID, { params })
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

  getLosses (id) {
    let url = ENDPOINT_LOSSES.formatUnicorn({ id })

    return this.$http.get(url)
  }

  createLoss (id, data) {
    let url = ENDPOINT_LOSSES.formatUnicorn({ id })

    return this.$http.post(url, data)
  }

  updateLoss (id, lid, data) {
    let url = ENDPOINT_LOSSES_SINGLE.formatUnicorn({ id, lid })

    return this.$http.patch(url, data)
  }

  deleteLoss (id, lid) {
    let url = ENDPOINT_LOSSES_SINGLE.formatUnicorn({ id, lid })

    return this.$http.delete(url)
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

}