<template>
  <div>
    <Navbar :items="nav"></Navbar>
    <Sidebar :items="nav"></Sidebar>

    <div class="ui-container">
      <div class="router-view">
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
        <p class="title">dwenfifweflkwnflwef</p>
      </div>
    </div>
  </div>
</template>

<script>
  import Navbar from './elements/Navbar'
  import Sidebar from './elements/Sidebar'

  export default {
    components: { Navbar, Sidebar },

    data () {
      return {
        nav: [
          {
            route: { to: '/' },
            icon: 'home',
            text: 'Home'
          },

          {
            route: { to: '/' },
            icon: 'users',
            text: 'Accounts'
          }
        ]
      }
    }
  }
</script>
