<template>
  <section>
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <h1 class="title">Accounts</h1>
        </div>
      </div>

      <div class="level-right">
        <div class="level-item">
          <button class="button is-primary" @click="modal.create = true">
            <span class="icon">
              <i class="fa fa-plus"></i>
            </span>
            
            <span>Create Account</span>
          </button>
        </div>
      </div>
    </div>

    <accounts></accounts>

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
  import create from '@admin/partials/accounts/create'
  import edit from '@admin/partials/accounts/edit'

  export default {
    components: { accounts, create, edit },

    data () {
      return {
        modal: {
          create: false,
          edit: false
        },

        mountedModel: null
      }
    },

    mounted () {
      this.$root.$on('accounts.edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    }
  }
</script>
