<template>
  <b-table paginated striped backend-pagination
    
    :data="result.data"
    :total="result.total"
    :per-page="result.per_page"
    :loading="isLoading"
    :mobile-cards="false"

    @page-change="onPageChange">

    <template slot-scope="props">
      <b-table-column field="name" label="Name" sortable>
        <article class="media is-vcentered">
          <figure class="media-left">
            <p class="image is-48x48">
              <img :src="props.row.picture.thumb" />
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
        </article>
      </b-table-column>
      
      <b-table-column field="group" label="Group" sortable>
        {{ props.row.group ? props.row.group.name : 'N/A' }}
      </b-table-column>
      
      <b-table-column field="last_login_at" label="Last login at" sortable>
        {{ props.row.last_login_at ? props.row.last_login_at : 'None recorded' }}
      </b-table-column>
      
      <b-table-column class="is-fit">
        <div class="dropdown is-hoverable is-right" v-if="$root.account.id != props.row.id">
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
                @click.prevent="redirectToProfile(props.row)">
                
                <span class="icon is-small">
                  <i class="fas fa-eye"></i>
                </span>

                <span>View Profile</span>
              </a>

              <a href="#" class="dropdown-item"
                @click.prevent="editModel(props.row)"
                v-if="$root.can('edit_account')">
                
                <span class="icon is-small">
                  <i class="fa fa-edit"></i>
                </span>

                <span>Edit</span>
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
      </b-table-column>
    </template>
  </b-table>
</template>

<script>
  let deferPageChange = false

  export default {
    inject: ['$account'],

    data () {
      return {
        result: [],
        query: {},

        isLoading: false
      }
    },

    mounted () {
      this.refresh()

      this.$root.$on('model:saved', model => {
        this.refresh()
      })

      this.$root.$on('model:query', query => {
        this.query = query

        this.refresh()
      })
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

      departmentsModel (model) {
        this.$root.edits.model = model

        this.$root.$showModal('model_edit_department')
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

      _haltPageChangeEvent () {
        deferPageChange = true

        setTimeout(() => deferPageChange = false, 1000)
      }
    }
  }
</script>
