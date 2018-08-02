<template>
  <nav class="sidebar">
    <div class="navbar-brand">
      <img src="/assets/img/logo-admin.png">
    </div>

    <div class="search">
      <div class="field">
        <p class="control has-icons-left">
          <input class="input" type="text" placeholder="Type to search">

          <span class="icon is-small is-left">
            <i class="fas fa-search"></i>
          </span>
        </p>
      </div>
    </div>

    <div class="navbar-menu">
      <router-link class="navbar-item" v-for="(item, i) in items"
          :class="{ 'is-active': isNavItemActive(item.route.name) }"
          :to="item.route"
          :key="i">
        <span class="icon">
          <i :class="item.icon"></i>
        </span> {{ item.text }}
      </router-link>
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

    methods: {
      isNavItemActive (name) {
        if (!this.$route.name) {
          return false
        }

        return this.$route.name.indexOf(name) != -1
      }
    }
  }
</script>
