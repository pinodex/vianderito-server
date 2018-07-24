<template>
  <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <div class="navbar-item">
        <img src="/assets/img/vianderito-128.png">
      </div>

      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" @click="toggleMenu()">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div class="navbar-menu" :class="{ 'is-active': isMenuActive }">
      <div class="navbar-start">
        <router-link class="navbar-item" v-for="item in items" :to="item.route">
{{ item.text }}
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
</template>

<script>
  export default {
    props: {
      items: {
        type: Array,
        default: []
      }
    },

    data () {
      return {
        isMenuActive: false
      }
    },

    methods: {
      toggleMenu () {
        this.isMenuActive = !this.isMenuActive
      }
    }
  }  
</script>

