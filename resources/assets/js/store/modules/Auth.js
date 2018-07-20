const LOGIN = 'LOGIN',
      LOGOUT = 'LOGOUT',
      VERIFY = 'VERIFY',
      PUT_PASSWORD = 'PUT_PASSWORD'

const state = {
  user: null
}

const mutations = {
  [LOGIN] (state, user) {
    state.user = user
  },

  [LOGOUT] (state) {
    state.user = null
  },

  [VERIFY] (state) {
    state.user.is_verified = true
  },

  [PUT_PASSWORD] (state) {
    state.user.has_password = true
  }
}

const actions = {
  login({ commit }, user) {
    commit(LOGIN, user)
  },

  logout({ commit }) {
    commit(LOGOUT)
  },

  verify({ commit }) {
    commit(VERIFY)
  },

  putPassword({ commit }) {
    commit(PUT_PASSWORD)
  }
}

const getters = {
  user: state => {
    return state.user
  }
}

export default {
  state,
  mutations,
  actions,
  getters
}
