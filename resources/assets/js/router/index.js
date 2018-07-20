import Vue from 'vue'
import Router from 'vue-router'

const components = {

}

Vue.use(Router)

const router = new Router({
  mode: 'history',
  
  routes: [
  /*
    {
      path: '/',
      name: 'index',
      component: components.Index
    },
    */
  ]
})

router.beforeEach((to, from, next) => {
  router.app.$emit('router:start')

  router.app.$Progress.start()
  
  next()
})

router.afterEach(() => {
  router.app.$emit('router:finish')

  router.app.$Progress.finish()
})

export default router
