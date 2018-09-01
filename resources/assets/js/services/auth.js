const ENDPOINT_LOGIN = '/admin/login',
      ENDPOINT_LOGOUT = '/admin/logout',
      ENDPOINT_CHANGE_PASSWORD = '/admin/change_password',
      ENDPOINT_REQUEST_PASSWORD_RESET = '/admin/request_password_reset',
      ENDPOINT_RESET_PASSWORD = '/admin/reset_password/{id}'

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

  requestPasswordReset (params) {
    return this.$http.post(ENDPOINT_REQUEST_PASSWORD_RESET, params)
  }

  resetPassword (id, params) {
    let url = ENDPOINT_RESET_PASSWORD.formatUnicorn({ id })
    
    return this.$http.post(url, params)
  }

}