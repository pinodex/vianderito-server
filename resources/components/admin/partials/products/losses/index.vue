<template>
  <b-table striped
    :data="result"
    :loading="isLoading"
    :mobile-cards="false"
    :hoverable="true">

    <template slot-scope="props">
      <b-table-column field="inventory.eid" label="Inventory ID" sortable>
        {{ props.row.inventory.eid }}
      </b-table-column>

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
            <router-link class="button is-small"
              :to="{ name: 'inventories.losses', params: { id: props.row.inventory.id } }"
              v-if="$root.can('browse_inventories')">
                      
              <span class="icon is-small">
                <i class="fa fa-edit"></i>
              </span>

              <span>Manage Inventory Losses</span>
            </router-link>
          </p>
        </div>
      </b-table-column>
    </template>
  </b-table>
</template>

<script>
  export default {
    inject: ['$product'],

    props: {
      productId: {
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
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$product.getLosses(this.productId)
          .then(response => {
            if (response.data.length == 0) {
              this.$snackbar.open('The query returns no results')
            }

            this.result = response.data
          })
          .finally(() => {
            this.isLoading = false
          })
      }
    }
  }
</script>
