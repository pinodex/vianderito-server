<template>
  <section class="main-container">
    <tabs></tabs>

    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <div class="select">
            <select v-model="graphQuery.view">
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
        </div>

        <div class="level-item" v-if="graphQuery.view == 'yearly'">
          <div class="select">
            <select v-model="options.now.year">
              <option v-for="year in options.years" :value="year">{{ year }}</option>
            </select>
          </div>
        </div>

        <div class="level-item" v-if="graphQuery.view == 'monthly'">
          <div class="field has-addons">
            <p class="control">
              <span class="select">
                <select v-model="options.now.year">
                  <option v-for="year in options.years" :value="year">{{ year }}</option>
                </select>
              </span>
            </p>

            <p class="control">
              <span class="select">
                <select v-model="options.now.month">
                  <option v-for="(month, i) in options.months" :value="i">{{ month }}</option>
                </select>
              </span>
            </p>
          </div>
        </div>

        <div class="level-item" v-if="graphQuery.view == 'weekly'">
          <b-datepicker
            :date-formatter="formatDate"
            :date-parser="parseDate"
            v-model="graphQueryDate"
            placeholder="Select starting date" />
        </div>
      </div>
    </div>

    <column-chart :data="graphData" class="has-contents-below"></column-chart>

    <h2 class="subtitle">Sales Report</h2>

    <form @submit.prevent="search" class="has-contents-below">
      <div class="columns">
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
        <b-table-column field="eid" label="ID" sortable>
          {{ props.row.eid }}
        </b-table-column>

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

        <b-table-column field="cost" label="Cost" numeric sortable>
          {{ props.row.cost | currency('₱') }}
        </b-table-column>

        <b-table-column field="price" label="Price" numeric sortable>
          {{ props.row.price | currency('₱') }}
        </b-table-column>

        <b-table-column field="total_loss" label="Loss QTY" numeric sortable>
          {{ props.row.total_loss }}
        </b-table-column>

        <b-table-column field="total_loss_cost" label="Loss Cost" numeric sortable>
          {{ props.row.total_loss_cost | currency('₱') }}
        </b-table-column>

        <b-table-column field="total_loss_price" label="Loss Price" numeric sortable>
          {{ props.row.total_loss_price | currency('₱') }}
        </b-table-column>

        <b-table-column field="total_sale" label="Sale QTY" numeric sortable>
          {{ props.row.total_sale }}
        </b-table-column>

        <b-table-column field="total_sale_cost" label="Sales Cost" numeric sortable>
          {{ props.row.total_sale_cost | currency('₱') }}
        </b-table-column>

        <b-table-column field="total_sale_price" label="Sales Price" numeric sortable>
          {{ props.row.total_sale_price | currency('₱')}}
        </b-table-column>

        <b-table-column field="batch_date" label="Batch Date" centered sortable>
          {{ props.row.batch_date }}
        </b-table-column>
      </template>
    </b-table>
  </section>
</template>

<script>
  import moment from 'moment'
  import tabs from '@admin/partials/reports/tabs'

  export default {
    components: { tabs },

    inject: ['$report'],

    data () {
      return {
        graphData: {},
        
        graphQuery: {
          view: 'monthly'
        },

        graphQueryDate: null,

        options: {
          now: {}
        },

        query: {},
        result: [],

        isLoading: false
      }
    },

    mounted () {
      this.$root.setPageTitle('Sales Reports')

      this.$report.getSalesGraphOptions()
        .then(response => this.options = response.data)

      this.loadData()
    },

    watch: {
      graphQueryDate (value) {
        this.graphQuery.date = this.formatDate(value)

        this.loadGraph()
      },

      options: {
        handler (value) {
          this.setGraphQueryDateFromOptions()
        },

        deep: true
      },

      graphQuery: {
        handler (value) {
          this.loadGraph()
        },

        deep: true
      }
    },

    methods: {
      formatDate (date) {
        return moment(date).format('YYYY-MM-DD')
      },

      parseDate (date) {
        return moment(date).toDate()
      },

      search () {
        this.loadData()
      },

      setGraphQueryDateFromOptions () {
        let year = this.options.now.year,
            month = this.options.now.month

        if (month <= 9) {
          month = '0' + month
        }

        this.graphQuery.date = `${year}-${month}-01`

        this.loadGraph()
      },

      loadGraph () {
        const loadingComponent = this.$loading.open()

        this.$report.getSalesGraph(this.graphQuery)
          .then(response => this.graphData = response.data)
          .finally(() => loadingComponent.close())
      },

      loadData () {
        this.isLoading = true

        this.$report.getSales(this.query)
          .then(response => this.result = response.data)
          .finally(() => this.isLoading = false)
      }
    }
  }
</script>
