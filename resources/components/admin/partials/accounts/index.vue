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
        <router-link class="media is-vcentered"
          :to="{ name: 'accounts.view', params: { id: props.row.id }}">
          <figure class="media-left">
            <p class="image is-48x48 avatar">
              <img :src="props.row.picture.thumbnail" />
            </p>
          </figure>

          <div class="media-content">
            <div class="content">
              <p class="is-marginless">
                <strong>{{ props.row.name }}</strong><br />
                <small>@{{ props.row.username}}</small>
              </p>
            </div>
          </div>
        </router-link>
      </b-table-column>
      
      <b-table-column field="group" label="Group" sortable>
        {{ props.row.group ? props.row.group.name : 'N/A' }}
      </b-table-column>
      
      <b-table-column field="last_login_at" label="Last login at" sortable>
        <template v-if="props.row.last_login_at">
          <span :title="props.row.last_login_at | moment('MMM DD, YYYY hh:mm A')">
            {{ props.row.last_login_at | moment('from') }}
          </span>
        </template>

        <template v-else>
          None Recorded
        </template>
      </b-table-column>
      
      <b-table-column class="is-fit">
        <div class="field has-addons">
          <p class="control">
            <a href="#" class="button is-small"
              @click.prevent="editModel(props.row)"
              v-if="$root.can('edit_account')">
                
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
                    @click.prevent="enableModel(props.row)"
                    v-if="$root.can('status_account') && !props.row.is_enabled">

                    <span class="icon is-small">
                      <i class="fa fa-check"></i>
                    </span>

                    <span>Enable Account</span>
                  </a>

                  <a href="#" class="dropdown-item"
                    @click.prevent="disableModel(props.row)"
                    v-if="$root.can('status_account') && props.row.is_enabled">

                    <span class="icon is-small">
                      <i class="fa fa-times"></i>
                    </span>

                    <span>Disable Account</span>
                  </a>

                  <a href="#" class="dropdown-item"
                    @click.prevent="deleteModel(props.row)"
                    v-if="$root.can('delete_account')">
                    
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
      </b-table-column>
    </template>
  </b-table>
</template>

<script>
  let deferPageChange = false

  export default {
    inject: ['$account'],

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

      this.$root.$on('accounts:saved', model => this.refresh())

      this.$root.$on('accounts:query', () => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('accounts:saved')
      this.$root.$off('accounts:query')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.query.with = 'group'

        this.$account.get(this.query)
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
        window.location = this.$account.getModelUrl(row.id)
      },

      onPageChange (page) {
        if (deferPageChange) {
          return
        }

        let query = Object.assign({}, this.query, { page })

        this.isLoading = true

        this.$account.get(query)
          .then(response => {
            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      },

      editModel (model) {
        this.$root.$emit('accounts:edit', model)
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

            this.$account.delete(model.id)
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

      disableModel (model) {
        this.$dialog.confirm({
          type: 'is-warning',
          message: `Disable account ${model.name}?`,
          onConfirm: () => {
            this.$account.disable(model.id)
              .then(response => {
                model.is_enabled = false

                this.$toast.open({
                  message: `Account ${model.name} has been disabled`,
                  type: 'is-success'
                })
              })
          }
        })
      },

      enableModel (model) {
        this.$dialog.confirm({
          type: 'is-warning',
          message: `Enable account ${model.name}?`,
          onConfirm: () => { 
            this.$account.enable(model.id)
              .then(response => {
                model.is_enabled = true
                
                this.$toast.open({
                  message: `Account ${model.name} has been enabled`,
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
