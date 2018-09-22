<template>
  <b-table paginated striped backend-pagination
    
    :data="result.data"
    :total="result.total"
    :per-page="result.per_page"
    :loading="isLoading"
    :mobile-cards="false"
    :hoverable="true"

    @page-change="onPageChange">

    <template slot-scope="props">
      <b-table-column field="name" label="Name" sortable>
        <person :model="props.row"></person>
      </b-table-column>

      <b-table-column field="email_address" label="Email Address" sortable>
        {{ props.row.email_address ? props.row.email_address : 'N/A' }}
      </b-table-column>

      <b-table-column field="phone_number" label="Phone Number" sortable>
        {{ props.row.phone_number ? props.row.phone_number : 'N/A' }}
      </b-table-column>

      <b-table-column field="is_verified" label="Verification" sortable>
        {{ props.row.is_verified ? 'Verified' : 'Unverified' }}
      </b-table-column>
      
      <b-table-column field="created_at" label="Created at" sortable>
        <template v-if="props.row.created_at">
          {{ props.row.created_at | moment('MMM DD, YYYY hh:mm A') }}
        </template>

        <template v-else>
          None Recorded
        </template>
      </b-table-column>
      
      <b-table-column field="last_login_at" label="Last login at" sortable>
        <template v-if="props.row.last_login_at">
          {{ props.row.last_login_at | moment('MMM DD, YYYY hh:mm A') }}
        </template>

        <template v-else>
          None Recorded
        </template>
      </b-table-column>
      
      <b-table-column class="is-fit">
        <template v-if="!props.row.deleted_at">
          <div class="field has-addons">
            <p class="control">
              <a href="#" class="button is-small"
                @click.prevent="editModel(props.row)"
                v-if="$root.can('edit_user')">
                  
                <span class="icon is-small">
                  <i class="fa fa-edit"></i>
                </span>

                <span>Edit</span>
              </a>
            </p>
            <p class="control">
              <div class="dropdown is-hoverable is-right">
                <div class="dropdown-trigger">
                  <button class="button is-outlined is-small">
                    <span class="icon is-small">
                      <i class="fa fa-chevron-down"></i>
                    </span>
                  </button>
                </div>

                <div class="dropdown-menu">
                  <div class="dropdown-content">
                    <a href="#" class="dropdown-item"
                      @click.prevent="deleteModel(props.row)"
                      v-if="$root.can('delete_user')">
                      
                      <span class="icon is-small">
                        <i class="fa fa-trash"></i>
                      </span>

                      <span>Delete</span>
                    </a>
                  </div>
                </div>
              </div>
            </p>
          </div>
        </template>

        <template v-else>
          <div class="field is-grouped">
            <p class="control">
              <a href="#" class="button is-small is-info"
                @click.prevent="restoreModel(props.row)"
                v-if="$root.can('delete_user')">
                  
                <span class="icon is-small">
                  <i class="fa fa-check"></i>
                </span>

                <span>Restore</span>
              </a>
            </p>

             <p class="control">
              <a href="#" class="button is-small is-danger"
                @click.prevent="destroyModel(props.row)"
                v-if="$root.can('delete_user')">
                  
                <span class="icon is-small">
                  <i class="fa fa-trash-alt"></i>
                </span>

                <span>Destroy</span>
              </a>
            </p>
          </div>
        </template>
      </b-table-column>
    </template>
  </b-table>
</template>

<script>
  import person from './person'

  let deferPageChange = false

  export default {
    inject: ['$user'],

    components: { person },

    props: {
      query: {
        type: Object,
        default: {}
      }
    },

    data () {
      return {
        result: [],

        isLoading: false
      }
    },

    mounted () {
      this.refresh()

      this.$root.$on('users:saved', model => this.refresh())

      this.$root.$on('users:query', () => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('users:saved')
      this.$root.$off('users:query')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$user.get(this.query)
          .then(response => {
            this._haltPageChangeEvent()

            if (response.data.data.length == 0) {
              this.$snackbar.open('The query returns no results')
            }

            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      },

      redirectToProfile (row) {
        window.location = this.$user.getModelUrl(row.id)
      },

      onPageChange (page) {
        if (deferPageChange) {
          return
        }

        let query = Object.assign({}, this.query, { page })

        this.isLoading = true

        this.$user.get(query)
          .then(response => {
            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      },

      editModel (model) {
        this.$root.$emit('users:edit', model)
      },

      deleteModel (model) {
        this.$dialog.confirm({
          type: 'is-danger',
          message: `${model.name} will be deleted. Confirm?`,
          onConfirm: () => {
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }

            this.$user.delete(model.id)
              .then(response => {
                this.$toast.open({
                  message: `${model.name} has been deleted`,
                  type: 'is-success'
                })
              })
              .catch(error => {
                let message = error.response.data.message || error.response.data.error

                this.$dialog.alert({ message,
                  title: 'Error',
                  type: 'is-danger'
                })
                this.refresh()
              })
          }
        })
      },

      restoreModel (model) {
        this.$dialog.confirm({
          type: 'is-warning',
          message: `Restore user ${model.name}?`,
          onConfirm: () => {
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }
            
            this.$user.restore(model.id)
              .then(response => {
                this.$toast.open({
                  message: `User ${model.name} has been restored`,
                  type: 'is-success'
                })
              })
          }
        })
      },

      destroyModel (model) {
        this.$dialog.confirm({
          type: 'is-danger',
          message: `Delete user ${model.name} permanently?`,
          onConfirm: () => { 
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }

            this.$user.destroy(model.id)
              .then(response => {                
                this.$toast.open({
                  message: `User ${model.name} has been permanently deleted`,
                  type: 'is-success'
                })
              })
          }
        })
      },

      _haltPageChangeEvent () {
        deferPageChange = true

        setTimeout(() => deferPageChange = false, 1000)
      }
    }
  }
</script>
