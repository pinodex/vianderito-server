<template>
  <nav class="sidebar">
    <div class="navbar-brand">
      <img src="/assets/img/logo-admin.png">
    </div>

    <div class="navbar-menu">
      <router-link class="navbar-item" v-for="(item, i) in items"
          v-if="$root.can(item.permissions)"
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
