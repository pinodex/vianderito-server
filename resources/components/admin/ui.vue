<template>
  <div>
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <div class="navbar-item">
          <img src="/assets/img/vianderito-128.png">
        </div>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div class="navbar-menu">
        <div class="navbar-start">
          <router-link class="navbar-item"
            v-for="item in nav"
            :to="item.route">

            <span class="icon">
              <i :data-feather="item.icon"></i>
            </span> {{ item.text }}
          </router-link>
        </div>

        <div class="navbar-end">
          <a href="#" class="navbar-item has-dropdown is-hoverable account">
            <div class="navbar-link">{{ $root.account.name }}</div>

            <div class="navbar-dropdown">
              <div class="navbar-item">Logout</div>
            </div>
          </a>
        </div>
      </div>
    </nav>

    <nav class="sidebar">
      <div class="navbar-brand">
        <img src="/assets/img/logo-admin.png">
      </div>

      <div class="search">
        <div class="field">
          <p class="control has-icons-left">
            <input class="input" type="text" placeholder="Type to search">

            <span class="icon is-small is-left">
              <i data-feather="search"></i>
            </span>
          </p>
        </div>
      </div>

      <div class="navbar-menu">
        <router-link class="navbar-item"
          v-for="item in nav"
          :to="item.route">

          <span class="icon">
            <i :data-feather="item.icon"></i>
          </span> {{ item.text }}
        </router-link>
      </div>
    </nav>

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
  export default {
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
