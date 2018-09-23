<template>
  <b-table striped
    :data="result"
    :loading="isLoading"
    :mobile-cards="false"
    :hoverable="true">

    <template slot-scope="props">
      <b-table-column field="units" label="Units" sortable>
        {{ props.row.units }}
      </b-table-column>

      <b-table-column field="remarks" label="Remarks" sortable>
        {{ props.row.remarks }}
      </b-table-column>

      <b-table-column field="created_at" label="Date Added" sortable>
        {{ props.row.created_at | moment('MMM DD, YYYY hh:mm A') }}
      </b-table-column>
      
      <b-table-column class="is-fit">
        <div class="field has-addons">
          <p class="control">
            <button class="button is-small"
              @click.prevent="editModel(props.row)"
              v-if="$root.can('edit_inventory')">
                      
              <span class="icon is-small">
                <i class="fa fa-edit"></i>
              </span>

              <span>Edit</span>
            </button>
          </p>

          <p class="control">
            <button class="button is-small is-danger" 
              @click.prevent="deleteModel(props.row)"
              v-if="$root.can('edit_inventory')">
                      
              <span class="icon is-small">
                <i class="fa fa-trash"></i>
              </span>

              <span>Delete</span>
            </button>
          </p>
        </div>
      </b-table-column>
    </template>
  </b-table>
</template>

<script>
  export default {
    inject: ['$inventory'],

    props: {
      inventoryId: {
        type: String
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

      this.$root.$on('inventory_losses:saved', model => this.refresh())
    },

    beforeDestroy () {
      this.$root.$off('inventory_losses:saved')
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$inventory.getLosses(this.inventoryId)
          .then(response => {
            if (response.data.length == 0) {
              this.$snackbar.open('The query returns no results')
            }

            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      },

      editModel (model) {
        this.$root.$emit('inventory_losses:edit', model)
      },

      deleteModel (model) {
        this.$dialog.confirm({
          type: 'is-danger',
          message: `This inventory loss will be deleted. Confirm?`,
          onConfirm: () => {
            let index = this.result.findIndex(a => a.id == model.id)

            if (index > -1) {
              this.result.splice(index, 1)
            }

            this.$inventory.deleteLoss(this.inventoryId, model.id)
              .then(response => {
                this.$toast.open({
                  message: `Product inventory loss has been deleted`,
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
      }
    }
  }
</script>
