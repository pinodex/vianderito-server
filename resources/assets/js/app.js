import Vue from 'vue'
import ProgressBar from 'vue-progressbar'
import Moment from 'vue-moment'
import Datetime from 'vue-datetime'
import Filters from 'vue2-filters'
import Buefy from 'buefy'
import Axios from 'axios'

import store from './store'
import router from './router'
import services from './services'

import './group-by'
import '../../components/admin'

require('promise.prototype.finally').shim()

Vue.use(Buefy, {
  defaultIconPack: 'fa'
})

Vue.use(ProgressBar, {
  color: '#4289a5',
  failedColor: 'red',
  height: '2px'
})

Vue.use(Moment)

Vue.use(Datetime)

Vue.use(Filters)

Vue.prototype.$http = Axios.create({
  baseURL: '/papi/'
})

const app = new Vue({
  el: '#app',

  provide () {
    return services(this)
  },

  data () {
    return {
      account: window.__ACCOUNT__ || null,
      haltSessionCheck: false
    }
  },

  mounted () {
    if (this.account) {
      setInterval(() => this.checkSession(), 5000)
    }
  },

  methods: {
    setPageTitle (title) {
      document.title = `${title} - Vianderito`
    },

    checkSession () {
      if (this.haltSessionCheck) {
        return
      }

      this.$http.get('/session')
        .then(response => {
          if (!response.data.admin) {
            this.$emit('session:fail', 'not_logged_in')

            this.haltSessionCheck = true

            return
          }

          if (response.data.admin.id != this.account.id) {
            this.$emit('session:fail', 'id_mismatch')

            this.haltSessionCheck = true
          }

          if (!response.data.admin.is_enabled) {
            this.$emit('session:fail', 'account_disabled')

            this.haltSessionCheck = true
          }

          this.account = response.data.admin
        })
    },

    can (permission) {
      if (!this.account.group) {
        return false
      }

      if (permission == '*') {
        return true
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

      let index = this.account.group.permissions.findIndex(
        item => item.id == permission)

      return index !== -1
    }
  },

  store, router
})
