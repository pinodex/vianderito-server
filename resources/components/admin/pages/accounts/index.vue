<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <b-tabs @change="switchView">
                <b-tab-item label="Accounts"></b-tab-item>
                <b-tab-item label="Archive"></b-tab-item>
              </b-tabs>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item" v-if="$root.can('browse_accounts')">
              <div class="field has-addons">
                <namesearch :query="query" module="accounts" field="fullname"></namesearch>

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
                  <button class="button is-primary is-rounded" type="button"
                    @click="modal.create = true"
                    v-if="$root.can('create_account')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Create Account</span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <accounts :query="query"></accounts>
      </div>
    </div>

    <b-modal :active.sync="modal.create" :width="640">
      <div class="modal-box">
        <h1 class="modal-header">Create Account</h1>

        <div class="modal-body">
          <create></create>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="640">
      <div class="modal-box">
        <h1 class="modal-header">Edit Account</h1>

        <div class="modal-body">
          <edit :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="searchVisible" :width="360">
      <div class="modal-box">
        <h1 class="modal-header">Search Account</h1>

        <div class="modal-body">
          <search :query="query"></search>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import accounts from '@admin/partials/accounts/index'
  import search from '@admin/partials/accounts/search'
  import create from '@admin/partials/accounts/create'
  import edit from '@admin/partials/accounts/edit'

  import namesearch from '@admin/partials/namesearch'

  export default {
    components: { accounts, search, create, edit, namesearch },

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
      this.$root.setPageTitle('Accounts')
      
      this.$root.$on('accounts:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('accounts:query'), 1)
      })

      this.$root.$on('accounts:query', data => {
        this.isSearchActive = false
        
        for (var key in this.query) {
          if (this.query.hasOwnProperty(key)) {
            if (key == 'with' || key == 'trashed') continue

            if (this.query[key].length > 0) {
              this.isSearchActive = true
            }
          }
        }

        this.searchVisible = false
      })

      this.$root.$on('accounts:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    },

    beforeDestroy () {
      this.$root.$off('accounts:query:clear')
      
      this.$root.$off('accounts:edit')
    },

    methods: {
      switchView (view) {
        this.query.trashed = view

        this.$root.$emit('accounts:query')
      }
    }
  }
</script>
