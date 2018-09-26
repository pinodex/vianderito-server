<template>
  <div>
    <vue-progress-bar></vue-progress-bar>
    
    <template v-if="!hasFatalError">
      <navbar :items="nav"></navbar>
      <sidebar :items="nav"></sidebar>

      <div class="router-view">
        <div class="notification top-notification is-warning"
          v-if="$root.account.require_password_change && $route.name != 'change_password'">
          <div class="level">
            <div class="level-left">
              <div class="level-item">
                <strong>Please set a new password for your account!</strong>
              </div>
            </div>

            <div class="level-right">
              <div class="level-item">
                <router-link :to="{ name: 'change_password' }" class="button">
                  Change Password
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <div class="ui-container" :class="{ 'is-fullwidth': isUiFullWidth }">
          <transition name="fade" mode="out-in">
            <router-view></router-view>
          </transition>
        </div>
      </div>
    </template>

    <template v-else>
      <section class="hero is-fullheight">
        <div class="hero-body">
          <div class="container has-text-centered">
            <h1 class="is-size-2">Hello!</h1>

            <template v-if="errorReason == 'not_logged_in'">
              <p class="is-size-4">You are logged out from the system.</p>
              <p class="is-size-4 has-contents-below">Please login again</p>

              <div class="field">
                <p class="control has-text-centered">
                  <a href="/admin/login" class="button is-primary is-rounded">
                    <span class="icon">
                      <i class="fas fa-sign-in-alt"></i>
                    </span>

                    <span>Log back in</span>
                  </a>
                </p>
              </div>
            </template>

            <template v-if="errorReason == 'id_mismatch'">
              <p class="is-size-4">Another account has logged in to the system.</p>
              <p class="is-size-4 has-contents-below">Please refresh this page</p>

              <div class="field">
                <p class="control has-text-centered">
                  <a :href="'/admin' + $route.path" class="button is-primary is-rounded">
                    <span class="icon">
                      <i class="fas fa-sign-in-alt"></i>
                    </span>

                    <span>Refresh</span>
                  </a>
                </p>
              </div>
            </template>

            <template v-if="errorReason == 'account_disabled'">
              <p class="is-size-4 has-contents-below">Your account has been disabled.</p>

              <div class="field">
                <p class="control has-text-centered">
                  <a href="#" class="button is-primary is-rounded"
                    @click.prevent="logout">
                    <span class="icon">
                      <i class="fas fa-sign-in-alt"></i>
                    </span>

                    <span>Log out</span>
                  </a>
                </p>
              </div>
            </template>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>

<script>
  import navbar from '@admin/elements/navbar'
  import sidebar from '@admin/elements/sidebar'

  export default {
    inject: ['$auth'],

    components: { navbar, sidebar },

    data () {
      return {
        hasFatalError: false,
        errorReason: null,

        nav: [
          {
            route: { name: 'index' },
            icon: 'fa fa-home',
            text: 'Home',
            permissions: ['*']
          },

          {
            route: { name: 'accounts' },
            icon: 'fa fa-user',
            text: 'Accounts',
            permissions: ['browse_accounts']
          },

          {
            route: { name: 'departments' },
            icon: 'fa fa-users',
            text: 'Departments',
            permissions: ['browse_departments']
          },

          {
            route: { name: 'categories' },
            icon: 'fa fa-layer-group',
            text: 'Categories',
            permissions: ['browse_categories']
          },

          {
            route: { name: 'suppliers' },
            icon: 'fa fa-industry',
            text: 'Suppliers',
            permissions: ['browse_suppliers']
          },

          {
            route: { name: 'products' },
            icon: 'fa fa-box',
            text: 'Products',
            permissions: ['browse_products']
          },

          {
            route: { name: 'inventories' },
            icon: 'fa fa-boxes',
            text: 'Inventories',
            permissions: ['browse_inventories']
          },

          {
            route: { name: 'coupons' },
            icon: 'fa fa-ticket-alt',
            text: 'Coupons',
            permissions: ['browse_coupons']
          },

          {
            route: { name: 'users' },
            icon: 'fa fa-users',
            text: 'Users',
            permissions: ['browse_users']
          },

          {
            route: { name: 'reports' },
            icon: 'fa fa-chart-line',
            text: 'Reports',
            permissions: ['create_reports']
          }
        ]
      }
    },

    computed: {
      isUiFullWidth () {
        return ['accounts.view'].indexOf(this.$route.name) != -1
      }
    },

    methods: {
      logout () {
        this.$auth.logout()
          .finally(() => location.href = '/admin/')
      }
    },

    mounted () {
      if (!this.$root.account.is_enabled) {
        this.hasFatalError = true
        this.errorReason = 'account_disabled'
      }

      this.$root.$on('session:fail', reason => {
        this.hasFatalError = true
        this.errorReason = reason
      })
    }
  }
</script>
