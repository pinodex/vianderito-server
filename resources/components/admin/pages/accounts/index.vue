<template>
  <section>
    <div class="columns">
      <div class="column is-9">
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
                  <button class="button is-primary is-rounded" @click="modal.create = true">
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

        <accounts></accounts>
      </div>

      <div class="column">
         <div class="panel">
          <div class="panel-heading">
            <span class="icon is-small">
              <i class="fa fa-search"></i>
            </span>

            <span>Search</span>
          </div>

          <div class="panel-block">
            <search></search>
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

        mountedModel: null
      }
    },

    computed: {
      isSearchActive () {
        return !_.isEmpty(this.searchQuery)
      }
    },

    mounted () {
      this.$root.$on('accounts:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    }
  }
</script>
