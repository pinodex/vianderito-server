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
      <b-table-column field="eid" label="ID" sortable>
        {{ props.row.eid }}
      </b-table-column>

      <b-table-column field="product.name" label="Product" sortable>
        <section class="media is-vcentered">
          <figure class="media-left">
            <p class="image is-48x48">
              <img :src="props.row.product.picture.thumbnail">
            </p>
          </figure>

          <router-link class="media-content"
            :to="{ name: 'products', query: { id: props.row.product.id } }">
            <h1 class="is-size-6">{{ props.row.product.name }} </h1>

            <p class="is-size-7">
              {{ props.row.product.upc }}
            </p>
          </router-link>
        </section>
      </b-table-column>

      <b-table-column field="stocks" label="Stocks" class="has-text-right" sortable>
        {{ props.row.stocks }}
      </b-table-column>

      <b-table-column field="price" label="Price" class="has-text-right" sortable>
        {{ props.row.price }}
      </b-table-column>

      <b-table-column field="batch_date" label="Batch Date" sortable>
        {{ props.row.batch_date }}
      </b-table-column>

      <b-table-column field="expiration_date" label="Expiration" sortable>
        {{ props.row.expiration_date }}
      </b-table-column>

      <b-table-column field="created_at" label="Date Added" sortable>
        {{ props.row.created_at }}
      </b-table-column>
      
      <b-table-column class="is-fit">
        <div class="field has-addons">
          <p class="control">
            <router-link class="button is-small"
              :to="{ name: 'inventories.edit', params: { id: props.row.id } }"
              @click.prevent="editModel(props.row)"
              v-if="$root.can('edit_inventory')">
                    
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
                  <a href="#" class="dropdown-item"
                    @click.prevent="deleteModel(props.row)"
                    v-if="$root.can('delete_inventory')">
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
    inject: ['$inventory'],

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

      this.$root.$on('inventories:saved', model => this.refresh())

      this.$root.$on('inventories:query', () => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('inventories:saved')
      this.$root.$off('inventories:query')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$inventory.get(this.query)
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

        this.$inventory.get(query)
          .then(response => {
            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      },

      editModel (model) {
        this.$root.$emit('inventories:edit', model)
      },

      editModelPermissions (model) {
        this.$root.$emit('inventories:edit_permissions', model)
      },

      deleteModel (model) {
        this.$dialog.confirm({
          type: 'is-danger',
          message: `This inventory will be deleted. Confirm?`,
          onConfirm: () => {
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }

            this.$inventory.delete(model.id)
              .then(response => {
                this.$toast.open({
                  message: `Product inventory has been deleted`,
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
