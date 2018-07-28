<template>
  <div>
    <vue-progress-bar></vue-progress-bar>
    
    <navbar :items="nav"></navbar>
    <sidebar :items="nav"></sidebar>

    <div class="ui-container">
      <div class="router-view">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
  import navbar from '@admin/elements/navbar'
  import sidebar from '@admin/elements/sidebar'

  export default {
    components: { navbar, sidebar },

    data () {
      return {
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
    }
  }
</script>
