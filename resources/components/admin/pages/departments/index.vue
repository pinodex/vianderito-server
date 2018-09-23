<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <b-tabs @change="switchView">
                <b-tab-item label="Departments"></b-tab-item>
                <b-tab-item label="Archive"></b-tab-item>
              </b-tabs>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item" v-if="$root.can('browse_departments')">
              <div class="field">
                <namesearch :query="query" module="departments"></namesearch>
              </div>
            </div>

            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-primary is-rounded"
                    @click="modal.create = true"
                    v-if="$root.can('create_department')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Create Department</span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <departments :query="query"></departments>
      </div>
    </div>

    <b-modal :active.sync="modal.create" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Create Department</h1>

        <div class="modal-body">
          <create></create>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Edit Department</h1>

        <div class="modal-body">
          <edit :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.editPermissions" :width="640">
      <div class="modal-box">
        <h1 class="modal-header">Edit Department Permissions</h1>

        <div class="modal-body is-paddingless">
          <editPermissions :model="mountedModel"></editPermissions>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import departments from '@admin/partials/departments/index'
  import create from '@admin/partials/departments/create'
  import edit from '@admin/partials/departments/edit'
  import editPermissions from '@admin/partials/departments/edit-permissions'

  import namesearch from '@admin/partials/namesearch'

  export default {
    components: { departments, create, edit, editPermissions, namesearch },

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
      this.$root.setPageTitle('Departments')

      this.$root.$on('departments:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('departments:query'), 1)
      })

      this.$root.$on('departments:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })

      this.$root.$on('departments:edit_permissions', model => {
        this.modal.editPermissions = true

        this.mountedModel = model
      })

      if (this.$route.hash == '#add') {
        this.modal.create = true
      }
    },

    beforeDestroy () {
      this.$root.$off('departments:query:clear')
      this.$root.$off('departments:edit')
      this.$root.$off('departments:edit_permissions')
    },

    methods: {
      switchView (view) {
        this.query.trashed = view

        this.$root.$emit('departments:query')
      }
    }
  }
</script>
