<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <b-tabs @change="switchView">
                <b-tab-item label="Inventories"></b-tab-item>
                <b-tab-item label="Archive"></b-tab-item>
              </b-tabs>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-rounded"
                    :class="{ 'is-warning': isSearchActive }"
                    @click="searchVisible = true"
                    v-if="$root.can('browse_inventories')">
                    <span class="icon">
                      <i class="fa fa-search"></i>
                    </span>
                    
                    <span>Search</span>
                  </button>
                </p>

                <p class="control">
                  <router-link class="button is-primary is-rounded"
                    :to="{ name: 'inventories.add' }"
                    v-if="$root.can('create_inventory')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Add Inventory</span>
                  </router-link>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="tags legends">
          <span class="tag is-small">Normal</span>
          <span class="tag is-black is-small">No Stock</span>
          <span class="tag is-warning is-small">Needs to Order</span>
          <span class="tag is-danger is-small">Critical</span>
          <span class="tag is-info is-small">Overstocked</span>
        </div>

        <inventories :query="query"></inventories>
      </div>
    </div>

    <b-modal :active.sync="searchVisible" :width="360">
      <div class="modal-box">
        <h1 class="modal-header">Search Inventory</h1>

        <div class="modal-body">
          <search :query="query"></search>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import inventories from '@admin/partials/inventories/index'
  import search from '@admin/partials/inventories/search'

  export default {
    components: { inventories, search },

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
      this.$root.setPageTitle('Inventories')

      this.$root.$on('inventories:query:clear', () => {
        this.isSearchActive = false
        this.searchVisible = false
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('inventories:query'), 1)
      })

      this.$root.$on('inventories:query', data => {
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
      this.$root.$off('inventories:query:clear')
      
      this.$root.$off('inventories:query')
    },

    methods: {
      toggleSearch () {
        this.searchVisible = !this.searchVisible
      },

      switchView (view) {
        this.query.trashed = view

        this.$root.$emit('inventories:query')
      }
    }
  }
</script>
