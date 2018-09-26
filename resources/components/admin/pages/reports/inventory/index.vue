<template>
  <section class="main-container">
    <tabs></tabs>

    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <h2 class="subtitle">Summary by Inventory</h2>
        </div>

        <div class="level-item">
          <div class="select">
            <select v-model="summaryCriteria">
              <option value="created_at">Date Added</option>
              <option value="batch_date">Batch Date</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <line-chart :data="summaryData" class="has-contents-below"></line-chart>

    <h2 class="subtitle">Inventory Report</h2>

    <form @submit.prevent="search" class="has-contents-below">
      <div class="columns">
        <div class="column">
          <div class="field">
            <label class="label">Date Field</label>
            
            <div class="control">
              <div class="select is-fullwidth">
                <select v-model="query.date_field">
                  <option value="batch_date">Batch Date</option>
                  <option value="expiration_date">Expiration Date</option>
                  <option value="created_at">Date Added</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="column">
          <div class="field">
            <label class="label">From Date</label>

            <b-datepicker v-model="query.from_date" placeholder="Select starting date" />
          </div>
        </div>

        <div class="column">
          <div class="field">
            <label class="label">To Date</label>

            <b-datepicker v-model="query.to_date" placeholder="Select ending date" />
          </div>
        </div>

        <div class="column is-2">
          <div class="field">
            <label class="label">&zwnj;</label>

            <button type="submit" class="button is-primary is-fullwidth">
              <span class="icon is-small">
                <i class="fa fa-search"></i>
              </span>

              <span>Search</span>
            </button>
          </div>
        </div>
      </div>
    </form>

    <b-table striped
      :data="result"
      :loading="isLoading"
      :mobile-cards="false"
      :hoverable="true">

      <template slot-scope="props">
        <b-table-column field="product.upc" label="UPC" sortable>
          {{ props.row.product.upc }}
        </b-table-column>

        <b-table-column field="product.name" label="Product Name" sortable>
          {{ props.row.product.name }}
        </b-table-column>

        <b-table-column field="quantity" label="QTY" numeric sortable>
          {{ props.row.quantity }}
        </b-table-column>

        <b-table-column field="stocks" label="Remaining QTY" numeric sortable>
          {{ props.row.stocks }}
        </b-table-column>

        <b-table-column field="total_loss" label="Loss QTY" numeric sortable>
          {{ props.row.total_loss }}
        </b-table-column>

        <b-table-column field="total_sale" label="Sale QTY" numeric sortable>
          {{ props.row.total_sale }}
        </b-table-column>

        <b-table-column field="batch_date" label="Batch Date" centered sortable>
          {{ props.row.batch_date }}
        </b-table-column>

        <b-table-column field="expiration_date" label="Expiration Date" centered sortable>
          {{ props.row.expiration_date }}
        </b-table-column>

        <b-table-column field="created_at" label="Date Created" centered sortable>
          {{ props.row.created_at | moment('MMM DD, YYYY hh:mm A') }}
        </b-table-column>
      </template>
    </b-table>
  </section>
</template>

<script>
  import tabs from '@admin/partials/reports/tabs'

  export default {
    components: { tabs },

    inject: ['$report'],

    data () {
      return {
        summaryData: {},
        summaryCriteria: 'batch_date',

        query: {},
        result: [],

        isLoading: false
      }
    },

    mounted () {
      this.$root.setPageTitle('Inventory Reports')

      this.query.date_field = 'batch_date'

      this.loadSummary(this.summaryCriteria)
      this.loadData()
    },

    watch: {
      summaryCriteria (value) {
        this.loadSummary(value)
      }
    },

    methods: {
      search () {
        this.loadData()
      },

      loadSummary(field) {
        const loadingComponent = this.$loading.open()

        this.$report.getInventorySummary({ field })
          .then(response => this.summaryData = response.data)
          .finally(() => loadingComponent.close())
      },

      loadData() {
        this.isLoading = true

        this.$report.getInventory(this.query)
          .then(response => this.result = response.data)
          .finally(() => this.isLoading = false)
      }
    }
  }
</script>
