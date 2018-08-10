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
          :to="{ name: 'users.view', params: { id: props.row.id }}">
          <figure class="media-left">
            <p class="image is-48x48">
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
        {{ props.row.created_at ? props.row.created_at : 'None recorded' }}
      </b-table-column>
      
      <b-table-column field="last_login_at" label="Last login at" sortable>
        {{ props.row.last_login_at ? props.row.last_login_at : 'None recorded' }}
      </b-table-column>
      
      <b-table-column class="is-fit">
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
      </b-table-column>
    </template>
  </b-table>
</template>

<script>
  let deferPageChange = false

  export default {
    inject: ['$user'],

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

      _haltPageChangeEvent () {
        deferPageChange = true

        setTimeout(() => deferPageChange = false, 1000)
      }
    }
  }
</script>