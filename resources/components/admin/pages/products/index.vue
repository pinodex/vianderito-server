<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <b-tabs @change="switchView">
                <b-tab-item label="Products"></b-tab-item>
                <b-tab-item label="Archive"></b-tab-item>
              </b-tabs>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item" v-if="$root.can('browse_products')">
              <div class="field has-addons">
                <namesearch :query="query" module="products"></namesearch>

                <p class="control">
                  <button class="button is-rounded" title="More..."
                    :class="{ 'is-warning': isSearchActive }"
                    @click="searchVisible = true">
                    <span class="icon">
                      <i class="fa fa-ellipsis-h"></i>
                    </span>
                  </button>
                </p>
              </div>
            </div>

            <div class="level-item">
              <div class="field">
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
    </div>

    <b-modal :active.sync="searchVisible" :width="360">
      <div class="modal-box">
        <h1 class="modal-header">Search Product</h1>

        <div class="modal-body">
          <search :query="query"></search>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import products from '@admin/partials/products/index'
  import search from '@admin/partials/products/search'

  import namesearch from '@admin/partials/namesearch'

  export default {
    components: { products, search, namesearch },

    data () {
      return {
        mountedModel: null,
        query: {},

        searchVisible: false,
        isSearchActive: false
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.setPageTitle('Products')

      this.$root.$on('products:query:clear', () => {
        this.isSearchActive = false
        this.searchVisible = false
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('products:query'), 1)
      })

      this.$root.$on('products:query', data => {
        this.isSearchActive = false
        
        for (var key in this.query) {
          if (this.query.hasOwnProperty(key)) {
            if (key == 'with') continue

            if (this.query[key].length > 0) {
              this.isSearchActive = true
            }
          }
        }

        this.searchVisible = false
      })
    },

    beforeDestroy () {
      this.$root.$off('products:query:clear')
      
      this.$root.$off('products:query')
    },

    methods: {
      toggleSearch () {
        this.searchVisible = !this.searchVisible
      },

      switchView (view) {
        this.query.trashed = view

        this.$root.$emit('products:query')
      }
    }
  }
</script>
