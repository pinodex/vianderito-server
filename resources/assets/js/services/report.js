export default class {

  constructor (app) {
    this.$http = app.$http
  }

  getInventorySummary (params = {}) {
    return this.$http.get('/admin/reports/inventory/summary', { params })
  }

  getInventory (params = {}) {
    return this.$http.get('/admin/reports/inventory', { params })
  }
}
