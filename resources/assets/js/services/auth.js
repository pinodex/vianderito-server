const ENDPOINT_LOGIN = '/admin/login',
      ENDPOINT_LOGOUT = '/admin/logout',
      ENDPOINT_CHANGE_PASSWORD = '/admin/change_password'

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

  changePassword (params) {
    return this.$http.post(ENDPOINT_CHANGE_PASSWORD, params)
  }

}