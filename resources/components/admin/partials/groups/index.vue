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
        {{ props.row.name }}
      </b-table-column>
      
      <b-table-column field="members" label="Members">
        <router-link :to="{ name: 'accounts', query: { group_id: props.row.id } }">
          {{ props.row.accounts_count }} {{ props.row.accounts_count | pluralize('member') }}
        </router-link>
      </b-table-column>
      
      <b-table-column class="is-fit">
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
                @click.prevent="editModel(props.row)"
                v-if="$root.can('edit_group')">
                
                <span class="icon is-small">
                  <i class="fa fa-edit"></i>
                </span>

                <span>Edit</span>
              </a>

              <a href="#" class="dropdown-item"
                @click.prevent="deleteModel(props.row)"
                v-if="$root.can('delete_group')">
                
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
    inject: ['$group'],

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

      this.$root.$on('groups:saved', model => this.refresh())

      this.$root.$on('groups:query', () => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('groups:saved')
      this.$root.$off('groups:query')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$group.get(this.query)
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

      onPageChange (page) {
        if (deferPageChange) {
          return
        }

        let query = Object.assign({}, this.query, { page })

        this.isLoading = true

        this.$group.get(query)
          .then(response => {
            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      },

      editModel (model) {
        this.$root.$emit('groups:edit', model)
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

            this.$group.delete(model.id)
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
