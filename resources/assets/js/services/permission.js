const ENDPOINT_ALL = '/admin/permissions/all'

export default class {

  constructor (app) {
    this.$http = app.$http
  }

  all () {
    return this.$http.get(ENDPOINT_ALL)
  }

}