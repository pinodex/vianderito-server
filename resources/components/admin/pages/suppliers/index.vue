<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <b-tabs @change="switchView">
                <b-tab-item label="Suppliers"></b-tab-item>
                <b-tab-item label="Archive"></b-tab-item>
              </b-tabs>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item" v-if="$root.can('browse_suppliers')">
              <div class="field has-addons">
                <namesearch :query="query" module="suppliers"></namesearch>

                <p class="control">
                  <button class="button is-rounded" type="button" title="More..." 
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
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-primary is-rounded"
                    @click="modal.create = true"
                    v-if="$root.can('create_supplier')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Add Supplier</span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <suppliers :query="query"></suppliers>
      </div>
    </div>

    <b-modal :active.sync="modal.create" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Add Supplier</h1>

        <div class="modal-body">
          <create></create>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Edit Supplier</h1>

        <div class="modal-body">
          <edit :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="searchVisible" :width="360">
      <div class="modal-box">
        <h1 class="modal-header">Search Supplier</h1>

        <div class="modal-body">
          <search :query="query"></search>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import suppliers from '@admin/partials/suppliers/index'
  import search from '@admin/partials/suppliers/search'
  import create from '@admin/partials/suppliers/create'
  import edit from '@admin/partials/suppliers/edit'

  import namesearch from '@admin/partials/namesearch'

  export default {
    components: { suppliers, search, create, edit, namesearch },

    data () {
      return {
        modal: {
          create: false,
          edit: false
        },

        searchVisible: false,
        isSearchActive: false,
        mountedModel: null,
        query: {}
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.setPageTitle('Suppliers')

      this.$root.$on('suppliers:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('suppliers:query'), 1)
      })

      this.$root.$on('suppliers:query', data => {
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

      this.$root.$on('suppliers:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    },

    beforeDestroy () {
      this.$root.$off('suppliers:query')

      this.$root.$off('suppliers:query:clear')

      this.$root.$off('suppliers:edit')
    },

    methods: {
      switchView (view) {
        this.query.trashed = view

        this.$root.$emit('suppliers:query')
      }
    }
  }
</script>
