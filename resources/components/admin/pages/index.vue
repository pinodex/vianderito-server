<template>
  <section class="main-container">
    <div class="columns is-gapless shortcuts"  v-if="$root.can(['create_product', 'create_inventory', 'create_category', 'create_supplier'])">
      <div class="column is-2">
        <div class="header">Quick Launch</div>
      </div>

      <div class="column">
        <div class="level is-mobile">
          <router-link class="level-item" :to="{ name: 'products.add' }" v-if="$root.can('create_product')">
            <span class="icon is-small">
              <i class="fa fa-box"></i>
            </span>

            <span>Add Product</span>
          </router-link>

          <router-link class="level-item" :to="{ name: 'inventories.add' }" v-if="$root.can('create_inventory')">
            <span class="icon is-small">
              <i class="fa fa-boxes"></i>
            </span>

            <span>Add Inventory</span>
          </router-link>

          <router-link class="level-item" :to="{ name: 'categories', hash: '#add' }" v-if="$root.can('create_category')">
            <span class="icon is-small">
              <i class="fa fa-layer-group"></i>
            </span>

            <span>Add Category</span>
          </router-link>

          <router-link class="level-item" :to="{ name: 'suppliers', hash: '#add' }" v-if="$root.can('create_supplier')">
            <span class="icon is-small">
              <i class="fa fa-industry"></i>
            </span>

            <span>Add Supplier</span>
          </router-link>
        </div>
      </div>
    </div>

    <div class="columns is-hidden-mobile">
      <div class="column">
        <stat title="Users" :value="counts.users" background="/assets/img/users.svg" />
      </div>

      <div class="column">
        <stat title="Products" :value="counts.products" background="/assets/img/products.svg" />
      </div>

      <div class="column">
        <stat title="Inventories" :value="counts.inventories" background="/assets/img/inventories.svg" />
      </div>

      <div class="column">
        <stat title="Active Coupons" :value="counts.active_coupons" background="/assets/img/coupons.svg" />
      </div>
    </div>

    <div class="columns is-mobile is-multiline is-hidden-tablet">
      <div class="column is-half">
        <stat title="Users" :value="counts.users" background="/assets/img/users.svg" />
      </div>

      <div class="column is-half">
        <stat title="Products" :value="counts.products" background="/assets/img/products.svg" />
      </div>

      <div class="column is-half">
        <stat title="Inventories" :value="counts.inventories" background="/assets/img/inventories.svg" />
      </div>

      <div class="column is-half">
        <stat title="Active Coupons" :value="counts.active_coupons" background="/assets/img/coupons.svg" />
      </div>
    </div>

    <div class="columns" v-if="$root.can('create_reports')">
      <div class="column">
        <div class="box">
          <div class="level is-mobile">
            <div class="level-left">
              <h2 class="subtitle">Inventory</h2>
            </div>

            <div class="level-right">
              <router-link :to="{ name: 'reports.inventory' }">View All</router-link>
            </div>
          </div>

          <area-chart :data="inventoryGraph"></area-chart>
        </div>
      </div>

      <div class="column">
        <div class="box">
          <div class="level is-mobile">
            <div class="level-left">
              <h2 class="subtitle">Sales</h2>
            </div>

            <div class="level-right">
              <router-link :to="{ name: 'reports.sales' }">View All</router-link>
            </div>
          </div>

          <area-chart :data="salesGraph"></area-chart>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
  .shortcuts {
    background: #fff;
    border-radius: 6px;
    overflow: hidden;

    .header {
      background: #52a4d5;
      text-align: center;
      padding: 1rem;
      color: #fff;
    }

    .level {
      height: 100%;
    }

    @media (max-width: 768px) {
      .header {
        padding: 0.5rem;
      }
      
      .level-item {
        padding: 1rem;
      }
    }

    @media (max-width: 630px) {
      .icon + span {
        display: none;
      }
    }

    .icon + span {
      margin-left: 0.5rem;
    }
  }
</style>

<script>
  import stat from '@admin/partials/stat'

  export default {
    inject: ['$stats', '$report'],

    components: { stat },

    data () {
      return {
        counts: {},
        inventoryGraph: [],
        salesGraph: []
      }
    },

    mounted () {
      this.$root.setPageTitle('Home')

      this.$stats.counts().then(response => {
        this.counts = response.data
      })

      this.$report.getInventoryGraph()
        .then(response => this.inventoryGraph = response.data)

      this.$report.getSalesGraph({ view: 'yearly' })
        .then(response => this.salesGraph = response.data)
    }
  }
</script>
