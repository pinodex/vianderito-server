<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Accounts</h1>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-primary is-rounded"
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

      <div class="column is-3" v-if="$root.can('browse_accounts')">
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
  </section>
</template>

<script>
  import accounts from '@admin/partials/accounts/index'
  import search from '@admin/partials/accounts/search'
  import create from '@admin/partials/accounts/create'
  import edit from '@admin/partials/accounts/edit'

  export default {
    components: { accounts, search, create, edit },

    data () {
      return {
        modal: {
          create: false,
          edit: false
        },

        mountedModel: null,
        query: {}
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.$on('accounts:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('accounts:query'), 1)
      })

      this.$root.$on('accounts:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    },

    beforeDestroy () {
      this.$root.$off('accounts:query:clear')
      this.$root.$off('accounts:edit')
    }
  }
</script>
