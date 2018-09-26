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
    data () {
      return {
        tabs: [
          {
            route: { name: 'reports.inventory' },
            text: 'Inventory',
            permissions: ['create_reports']
          },

          {
            route: { name: 'reports.purchase' },
            text: 'Purchases',
            permissions: ['create_reports']
          }
        ]
      }
    }
  }
</script>
