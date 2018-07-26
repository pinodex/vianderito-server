import Vue from 'vue'
import ProgressBar from 'vue-progressbar'
import Buefy from 'buefy'
import Axios from 'axios'

import store from './store'
import router from './router'
import services from './services'

import '../../components/admin'

Vue.use(Buefy, {
  defaultIconPack: 'fa',
  defaultDateFormatter: (date) => date.toISOString().split('T')[0]
})

Vue.use(ProgressBar, {
  color: '#4289a5',
  failedColor: 'red',
  height: '2px'
})

Vue.prototype.$http = Axios.create({
  baseURL: window.API_BASE
})

const app = new Vue({
  el: '#app',

  provide () {
    return services(this)
  },

  data () {
    return {
      account: window.__ACCOUNT__ || null
    }
  },

  methods: {
    setPageTitle (title) {
      document.title = `${title} - Vianderito`
    },

    can (permission) {
      if (!this.account.group) {
        return false
      }

      if (permission.constructor === Array) {
        for (var i = 0; i < permission.length; i++) {
          let status = this.can(permission[i])

          if (status) {
            return true
          }
        }

        return false
      }

      let index = this.account.group.permissions.findIndex(item => item.id == permission)

      return index !== -1
    }
  },

  store, router
})
