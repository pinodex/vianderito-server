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
      path: '/accounts/:id',
      name: 'accounts.view',
      component: () => import('@admin/pages/accounts/view')
    },

    {
      path: '/groups',
      name: 'groups',
      component: () => import('@admin/pages/groups/index')
    },

    {
      path: '/categories',
      name: 'categories',
      component: () => import('@admin/pages/categories/index')
    },

    {
      path: '/manufacturers',
      name: 'manufacturers',
      component: () => import('@admin/pages/manufacturers/index')
    },

    {
      path: '/products',
      name: 'products',
      component: () => import('@admin/pages/products/index')
    },

    {
      path: '/products/add',
      name: 'products.add',
      component: () => import('@admin/pages/products/add')
    },

    {
      path: '/products/:id/edit',
      name: 'products.edit',
      component: () => import('@admin/pages/products/edit')
    },

    {
      path: '/users',
      name: 'users',
      component: () => import('@admin/pages/users/index')
    },

    {
      path: '/users/:id',
      name: 'users.view',
      component: () => import('@admin/pages/users/view')
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
