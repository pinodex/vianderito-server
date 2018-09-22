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
      <b-table-column field="code" label="Code" sortable>
        {{ props.row.code }}
      </b-table-column>
      
      <b-table-column field="validity_start" label="Validity Start" sortable>
        {{ props.row.validity_start | moment('MMM DD, YYYY hh:mm A') }}
      </b-table-column>

      <b-table-column field="validity_end" label="Validity End" sortable>
        {{ props.row.validity_end | moment('MMM DD, YYYY hh:mm A') }}
      </b-table-column>
      
      <b-table-column class="is-fit">
        <template v-if="!props.row.deleted_at">
          <div class="field has-addons">
            <p class="control">
              <router-link class="button is-small"
                :to="{ name: 'coupons.edit', params: { id: props.row.id } }"
                v-if="$root.can('edit_coupon')">
                  
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
                      v-if="$root.can('delete_coupon')">
                      
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
                v-if="$root.can('delete_coupon')">
                  
                <span class="icon is-small">
                  <i class="fa fa-check"></i>
                </span>

                <span>Restore</span>
              </a>
            </p>

             <p class="control">
              <a href="#" class="button is-small is-danger"
                @click.prevent="destroyModel(props.row)"
                v-if="$root.can('delete_coupon')">
                  
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
    inject: ['$coupon'],

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

      this.$root.$on('coupons:saved', model => this.refresh())

      this.$root.$on('coupons:query', () => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('coupons:saved')
      this.$root.$off('coupons:query')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$coupon.get(this.query)
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

        this.$coupon.get(query)
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
          message: `${model.code} will be deleted. Confirm?`,
          onConfirm: () => {
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }

            this.$coupon.delete(model.id)
              .then(response => {
                this.$toast.open({
                  message: `${model.code} has been deleted`,
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
          message: `Restore coupon ${model.code}?`,
          onConfirm: () => {
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }
            
            this.$coupon.restore(model.id)
              .then(response => {
                this.$toast.open({
                  message: `Coupon ${model.code} has been restored`,
                  type: 'is-success'
                })
              })
          }
        })
      },

      destroyModel (model) {
        this.$dialog.confirm({
          type: 'is-danger',
          message: `Delete coupon ${model.code} permanently?`,
          onConfirm: () => { 
            let index = this.result.data.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.data.splice(index, 1)
            }

            this.$coupon.destroy(model.id)
              .then(response => {                
                this.$toast.open({
                  message: `Coupon ${model.code} has been permanently deleted`,
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
