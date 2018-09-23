const ENDPOINT_COUNTS = '/admin/stats/counts'

export default class {

  constructor (app) {
    this.$http = app.$http
  }

  counts () {
    return this.$http.get(ENDPOINT_COUNTS)
  }

}