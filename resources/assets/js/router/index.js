import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  base: 'admin',
  mode: 'history',

  routes: [
    {
      path: '/',
      name: 'index',
      component: () => import('@admin/pages/index')
    },

    {
      path: '/accounts',
      name: 'accounts',
      component: () => import('@admin/pages/accounts/index')
    },

    {
      path: '/groups',
      name: 'groups',
      component: () => import('@admin/pages/groups/index')
    },
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
