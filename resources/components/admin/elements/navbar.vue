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
        <router-link class="navbar-item" v-for="(item, i) in items"
            :class="{ 'is-active': isNavItemActive(item.route.name) }"
            :to="item.route"
            :key="i">
          {{ item.text }}
        </router-link>
      </div>

      <div class="navbar-end">
        <div class="navbar-item has-dropdown is-hoverable account">
          <div class="navbar-link">{{ $root.account.name }}</div>

          <div class="navbar-dropdown">
            <a href="#" class="navbar-item" @click.prevent="logout()">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
  export default {
    inject: ['$auth'],

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

    mounted () {
      this.$root.$on('router:start', () => this.isMenuActive = false)
    },

    methods: {
      toggleMenu () {
        this.isMenuActive = !this.isMenuActive
      },

       isNavItemActive (name) {
        if (!this.$route.name) {
          return false
        }

        return this.$route.name.indexOf(name) != -1
      },

      logout () {
        this.isMenuActive = false

        this.$dialog.confirm({
          message: 'Are you sure you want to logout?',
          onConfirm: () => {
            const loadingComponent = this.$loading.open()

            this.$auth.logout()
              .then(response => {
                this.$root.$emit('logout:done', response.data)
              })
              .finally(() => location.href = '/admin/')
          }
        })
      }
    }
  }  
</script>

