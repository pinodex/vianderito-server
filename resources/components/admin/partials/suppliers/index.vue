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
        {{ props.row.name }}
      </b-table-column>

      <b-table-column field="code" label="Code" sortable>
        {{ props.row.code }}
      </b-table-column>

      <b-table-column field="name" label="Products" sortable>
        <template v-if="!props.row.deleted_at">
          <router-link :to="{ name: 'products', query: { supplier_id: props.row.id } }">
            {{ props.row.products_count }} {{ props.row.products_count | pluralize('product') }}
          </router-link>
        </template>

        <template v-else>
          {{ props.row.products_count }} {{ props.row.products_count | pluralize('product') }}
        </template>
      </b-table-column>
      
      <b-table-column class="is-fit">
        <template v-if="!props.row.deleted_at">
          <div class="field has-addons">
            <p class="control">
              <a href="#" class="button is-small"
                @click.prevent="editModel(props.row)"
                v-if="$root.can('edit_supplier')">
                      
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
                      v-if="$root.can('delete_supplier')">
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
                v-if="$root.can('delete_supplier')">
                  
                <span class="icon is-small">
                  <i class="fa fa-check"></i>
                </span>

                <span>Restore</span>
              </a>
            </p>

             <p class="control">
              <a href="#" class="button is-small is-danger"
                @click.prevent="destroyModel(props.row)"
                v-if="$root.can('delete_supplier')">
                  
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
  let deferPageChange = false

  export default {
    inject: ['$supplier'],

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

      this.$root.$on('suppliers:saved', model => this.refresh())

      this.$root.$on('suppliers:query', () => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('suppliers:saved')
      this.$root.$off('suppliers:query')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$supplier.get(this.query)
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

        this.$supplier.get(query)
          .then(response => {
            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      },

      editModel (model) {
        this.$root.$emit('suppliers:edit', model)
      },

      editModelPermissions (model) {
        this.$root.$emit('suppliers:edit_permissions', model)
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

            this.$supplier.delete(model.id)
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
          message: `Restore supplier ${model.name}?`,
          onConfirm: () => {
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }
            
            this.$supplier.restore(model.id)
              .then(response => {
                this.$toast.open({
                  message: `Supplier ${model.name} has been restored`,
                  type: 'is-success'
                })
              })
          }
        })
      },

      destroyModel (model) {
        this.$dialog.confirm({
          type: 'is-danger',
          message: `Delete supplier ${model.name} permanently?`,
          onConfirm: () => { 
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }

            this.$supplier.destroy(model.id)
              .then(response => {                
                this.$toast.open({
                  message: `Supplier ${model.name} has been permanently deleted`,
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
