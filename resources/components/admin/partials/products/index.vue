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
        {{ props.row.category.name }}
      </b-table-column>

      <b-table-column field="manufacturer" label="Manufacturer" sortable>
        {{ props.row.manufacturer.name }}
      </b-table-column>
      
      <b-table-column class="is-fit">
        <div class="field has-addons">
          <p class="control">
            <router-link class="button is-small"
              :to="{ name: 'products.edit', params: { id: props.row.id } }"
              @click.prevent="editModel(props.row)"
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

        this.query.with = 'category,manufacturer'

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

      editModel (model) {
        this.$root.$emit('products:edit', model)
      },

      editModelPermissions (model) {
        this.$root.$emit('products:edit_permissions', model)
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

      _haltPageChangeEvent () {
        deferPageChange = true

        setTimeout(() => deferPageChange = false, 1000)
      }
    }
  }
</script>
