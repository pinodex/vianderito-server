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
      <b-table-column class="is-fit">
        <figure class="image is-32x32">
          <img :src="props.row.picture.thumbnail">
        </figure>
      </b-table-column>

      <b-table-column field="name" label="Name" sortable>
        {{ props.row.name }}
      </b-table-column>

      <b-table-column field="code" label="UPC" sortable>
        {{ props.row.upc }}
      </b-table-column>

      <b-table-column field="category" label="Category" sortable>
        <template v-if="props.row.category">
          <router-link :to="{ name: 'categories', query: { id: props.row.category.id } }">
            {{ props.row.category.name }}
          </router-link>
        </template>

        <template v-else>N/A</template>
      </b-table-column>

      <b-table-column field="supplier" label="Supplier" sortable>
        <template v-if="props.row.supplier">
          <router-link :to="{ name: 'suppliers', query: { id: props.row.supplier.id } }">
            {{ props.row.supplier.name }}
          </router-link>
        </template>

        <template v-else>N/A</template>
      </b-table-column>
      
      <b-table-column class="is-fit">
        <template v-if="!props.row.deleted_at">
          <div class="field has-addons">
            <p class="control">
              <router-link class="button is-small"
                :to="{ name: 'products.edit', params: { id: props.row.id } }"
                v-if="$root.can('edit_product')">
                      
                <span class="icon is-small">
                  <i class="fa fa-edit"></i>
                </span>

                <span>Edit</span>
              </router-link>
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
                    <router-link class="dropdown-item"
                      :to="{ name: 'inventories', query: { product_id: props.row.id } }"
                      v-if="$root.can('browse_inventories')">
                      <span class="icon is-small">
                        <i class="fa fa-boxes"></i>
                      </span>

                      <span>Inventories</span>
                    </router-link>

                    <a href="#" class="dropdown-item"
                      @click.prevent="deleteModel(props.row)"
                      v-if="$root.can('delete_product')">
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
                v-if="$root.can('delete_product')">
                  
                <span class="icon is-small">
                  <i class="fa fa-check"></i>
                </span>

                <span>Restore</span>
              </a>
            </p>

             <p class="control">
              <a href="#" class="button is-small is-danger"
                @click.prevent="destroyModel(props.row)"
                v-if="$root.can('delete_product')">
                  
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
    inject: ['$product'],

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

      this.$root.$on('products:saved', model => this.refresh())

      this.$root.$on('products:query', () => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('products:saved')
      this.$root.$off('products:query')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.query.with = 'category,supplier,epcs'

        this.$product.get(this.query)
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

        this.$product.get(query)
          .then(response => {
            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
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

            this.$product.delete(model.id)
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
          message: `Restore product ${model.name}?`,
          onConfirm: () => {
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }
            
            this.$product.restore(model.id)
              .then(response => {
                this.$toast.open({
                  message: `Product ${model.name} has been restored`,
                  type: 'is-success'
                })
              })
          }
        })
      },

      destroyModel (model) {
        this.$dialog.confirm({
          type: 'is-danger',
          message: `Delete product ${model.name} permanently?`,
          onConfirm: () => { 
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }

            this.$product.destroy(model.id)
              .then(response => {                
                this.$toast.open({
                  message: `Product ${model.name} has been permanently deleted`,
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
