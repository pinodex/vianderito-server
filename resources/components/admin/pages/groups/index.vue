<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Groups</h1>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-primary is-rounded"
                    @click="modal.create = true"
                    v-if="$root.can('create_group')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Create Group</span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <groups :query="query"></groups>
      </div>

      <div class="column is-3" v-if="$root.can('browse_groups')">
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

    <b-modal :active.sync="modal.create" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Create Group</h1>

        <div class="modal-body">
          <create></create>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Edit Group</h1>

        <div class="modal-body">
          <edit :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.editPermissions" :width="640">
      <div class="modal-box">
        <h1 class="modal-header">Edit Group Permissions</h1>

        <div class="modal-body is-paddingless">
          <editPermissions :model="mountedModel"></editPermissions>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import groups from '@admin/partials/groups/index'
  import search from '@admin/partials/groups/search'
  import create from '@admin/partials/groups/create'
  import edit from '@admin/partials/groups/edit'
  import editPermissions from '@admin/partials/groups/edit-permissions'

  export default {
    components: { groups, search, create, edit, editPermissions },

    data () {
      return {
        modal: {
          create: false,
          edit: false,
          editPermissions: false
        },

        mountedModel: null,
        query: {}
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.$on('groups:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('groups:query'), 1)
      })

      this.$root.$on('groups:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })

      this.$root.$on('groups:edit_permissions', model => {
        this.modal.editPermissions = true

        this.mountedModel = model
      })
    },

    beforeDestroy () {
      this.$root.$off('groups:query:clear')
      this.$root.$off('groups:edit')
      this.$root.$off('groups:edit_permissions')
    }
  }
</script>
