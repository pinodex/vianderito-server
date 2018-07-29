<template>
  <div>
    <vue-progress-bar></vue-progress-bar>
    
    <template v-if="!hasFatalError">
      <navbar :items="nav"></navbar>
      <sidebar :items="nav"></sidebar>

      <div class="ui-container">
        <div class="router-view">
          <router-view></router-view>
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
    components: { navbar, sidebar },

    data () {
      return {
        hasFatalError: false,
        errorReason: null,

        nav: [
          {
            route: { name: 'index' },
            icon: 'fa fa-home',
            text: 'Home'
          },

          {
            route: { name: 'accounts' },
            icon: 'fa fa-user',
            text: 'Accounts'
          },

          {
            route: { name: 'groups' },
            icon: 'fa fa-users',
            text: 'Groups'
          }
        ]
      }
    },

    mounted () {
      this.$root.$on('session:fail', reason => {
        this.hasFatalError = true
        this.errorReason = reason
      })
    }
  }
</script>
