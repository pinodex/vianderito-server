import Vue from 'vue'
import ProgressBar from 'vue-progressbar'
import Moment from 'vue-moment'
import Filters from 'vue2-filters'
import Buefy from 'buefy'
import Axios from 'axios'

import store from './store'
import router from './router'
import services from './services'

import './group-by'
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

Vue.use(Moment)

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
      account: window.__ACCOUNT__ || null
    }
  },

  mounted () {
    if (this.account) {
      setInterval(() => this.checkSession(), 5000)

      this.$on('router:start', () => this.checkPermissions())
    }

    this.$on('session:evaluate_permissions', () =>
     this.checkPermissions())
  },

  methods: {
    setPageTitle (title) {
      document.title = `${title} - Vianderito`
    },

    checkSession () {
      this.$http.get('/session')
        .then(response => {
          if (!response.data.admin) {
            this.$emit('session:fail', 'not_logged_in')

            return
          }

          if (response.data.admin.id != this.account.id) {
            this.$emit('session:fail', 'id_mismatch')
          }
        })
    },

    checkPermissions () {
      this.$http.get('/admin/permissions')
        .then(response => {
          if (this.account.group) {
            this.account.group.permissions = response.data
          }
        })
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
