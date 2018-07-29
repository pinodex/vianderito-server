const ENDPOINT_LOGIN = '/admin/login',
      ENDPOINT_LOGOUT = '/admin/logout'

export default class {

  constructor (app) {
    this.$http = app.$http
  }

  login (params) {
    return this.$http.post(ENDPOINT_LOGIN, params)
  }

  logout (params) {
    return this.$http.post(ENDPOINT_LOGOUT, params)
  }

}