export default class {

  constructor (app) {
    this.$http = app.$http
  }

  getInventoryGraph (params = {}) {
    return this.$http.get('/admin/reports/inventory/graph', { params })
  }

  getInventory (params = {}) {
    return this.$http.get('/admin/reports/inventory', { params })
  }

  getSalesGraph (params = {}) {
    return this.$http.get('/admin/reports/sales/graph', { params })
  }

  getSalesGraphOptions () {
    return this.$http.get('/admin/reports/sales/graph/options')
  }

  getSales (params = {}) {
    return this.$http.get('/admin/reports/sales', { params })
  }
}
