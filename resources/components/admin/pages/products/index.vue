<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Products</h1>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <router-link class="button is-primary is-rounded"
                    :to="{ name: 'products.add' }"
                    @click="modal.create = true"
                    v-if="$root.can('create_product')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Add Product</span>
                  </router-link>
                </p>
              </div>
            </div>
          </div>
        </div>

        <products :query="query"></products>
      </div>

      <div class="column is-3" v-if="$root.can('browse_products')">
         <div class="panel">
          <div class="panel-heading">
            <span class="icon is-small">
              <i class="fa fa-search"></i>
            </span>

            <span>Search</span>
          </div>

          <div class="panel-block">
            <search :query="query"></search>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import products from '@admin/partials/products/index'
  import search from '@admin/partials/products/search'

  export default {
    components: { products, search },

    data () {
      return {
        mountedModel: null,
        query: {}
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.$on('products:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('products:query'), 1)
      })
    }
  }
</script>
