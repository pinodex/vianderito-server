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
            <i data-feather="search"></i>
          </span>
        </p>
      </div>
    </div>

    <div class="navbar-menu">
      <router-link class="navbar-item" v-for="item in items" :to="item.route">
        <span class="icon">
          <i :data-feather="item.icon"></i>
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
    }
  }  
</script>
