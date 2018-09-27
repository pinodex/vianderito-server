<template>
  <div class="tabs">
    <ul>
      <li v-for="tab in tabs" :class="{ 'is-active': $route.name == tab.route.name }">
        <router-link :to="tab.route">
          {{ tab.text }}
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    props: ['id'],

    data () {
      return {
        tabs: [
          {
            route: { name: 'users.view.purchases', params: { id: this.id } },
            text: 'Purchases'
          },

          {
            route: { name: 'users.view.payments', params: { id: this.id } },
            text: 'Payments'
          },

          {
            route: { name: 'users.view.payment_methods', params: { id: this.id } },
            text: 'Payment Methods'
          }
        ]
      }
    }
  }
</script>
