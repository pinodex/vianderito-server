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
      path: '/change_password',
      name: 'change_password',
      component: () => import('@admin/pages/change_password')
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
      path: '/departments',
      name: 'departments',
      component: () => import('@admin/pages/departments/index')
    },

    {
      path: '/categories',
      name: 'categories',
      component: () => import('@admin/pages/categories/index')
    },

    {
      path: '/suppliers',
      name: 'suppliers',
      component: () => import('@admin/pages/suppliers/index')
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
      path: '/products/:id/losses',
      name: 'products.losses',
      component: () => import('@admin/pages/products/losses/index')
    },

    {
      path: '/inventories',
      name: 'inventories',
      component: () => import('@admin/pages/inventories/index')
    },

    {
      path: '/inventories/add',
      name: 'inventories.add',
      component: () => import('@admin/pages/inventories/add')
    },

    {
      path: '/inventories/:id/edit',
      name: 'inventories.edit',
      component: () => import('@admin/pages/inventories/edit')
    },

    {
      path: '/inventories/:id/losses',
      name: 'inventories.losses',
      component: () => import('@admin/pages/inventories/losses/index')
    },

    {
      path: '/coupons',
      name: 'coupons',
      component: () => import('@admin/pages/coupons/index')
    },

    {
      path: '/coupons/create',
      name: 'coupons.create',
      component: () => import('@admin/pages/coupons/create')
    },

    {
      path: '/coupons/:id/edit',
      name: 'coupons.edit',
      component: () => import('@admin/pages/coupons/edit')
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

    {
      path: '/users/:id/purchases',
      name: 'users.view.purchases',
      component: () => import('@admin/pages/users/view')
    },

    {
      path: '/users/:id/payments',
      name: 'users.view.payments',
      component: () => import('@admin/pages/users/view')
    },

    {
      path: '/users/:id/payments/methods',
      name: 'users.view.payment_methods',
      component: () => import('@admin/pages/users/view')
    },

    {
      path: '/reports',
      name: 'reports',
      component: () => import('@admin/pages/reports/index')
    },

    {
      path: '/reports/inventory',
      name: 'reports.inventory',
      component: () => import('@admin/pages/reports/inventory/index')
    },

    {
      path: '/reports/purchase',
      name: 'reports.purchase',
      component: () => import('@admin/pages/reports/purchase/index')
    },

    {
      path: '/reports/sales',
      name: 'reports.sales',
      component: () => import('@admin/pages/reports/sales/index')
    }
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
