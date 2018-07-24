import Vue from 'vue'
import ProgressBar from 'vue-progressbar'
import Buefy from 'buefy'
import Axios from 'axios'
import feather from 'feather-icons'

import store from './store'
import router from './router'

import '../../components/admin'

Vue.use(Buefy, {
  defaultIconPack: 'fa',
  defaultDateFormatter: (date) => date.toISOString().split('T')[0]
})

Vue.use(ProgressBar, {
  color: '#00aeef',
  failedColor: 'red',
  height: '2px'
})

Vue.prototype.$http = Axios.create({
  baseURL: window.API_BASE
})

const app = new Vue({
  el: '#app',

  data () {
    return {
      account: window.__ACCOUNT__ || null
    }
  },

  mounted () {
    feather.replace()
  },

  methods: {
    setPageTitle (title) {
      document.title = `${title} - Vianderito`
    }
  },

  store, router
})
