<template>
  <section class="main-container">
    <tabs></tabs>

    <h2 class="subtitle">Purchase History</h2>

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

    <b-table striped detailed
      :data="result"
      :loading="isLoading"
      :mobile-cards="false"
      :hoverable="true">

      <template slot-scope="props">
        <b-table-column field="user" label="User" sortable>
          <person :model="props.row.user" />
        </b-table-column>
        
        <b-table-column field="created_at" label="Transaction Date" centered sortable>
          {{ props.row.created_at | moment('MMM DD, YYYY hh:mm A') }}
        </b-table-column>

        <b-table-column field="products.length" label="Items" numeric sortable>
          {{ props.row.products.length }}
        </b-table-column>

        <b-table-column field="coupon.code" label="Coupon Code" centered sortable>
          <template v-if="props.row.coupon">
            {{ props.row.coupon.code }}
          </template>

          <template v-else>N/A</template>
        </b-table-column>

        <b-table-column field="coupon.discount_price" label="Coupon Discount Price" numeric sortable>
          <template v-if="props.row.coupon && props.row.coupon.discount_price">
            {{ props.row.coupon.discount_price | currency('₱') }}
          </template>

          <template v-else>N/A</template>
        </b-table-column>

        <b-table-column field="coupon.discount_percentage" label="Coupon Discount Percentage" numeric sortable>
          <template v-if="props.row.coupon && props.row.coupon.discount_percentage">
            {{ props.row.coupon.discount_percentage + '%' }}
          </template>

          <template v-else>N/A</template>
        </b-table-column>

        <b-table-column field="discounted_amount" label="Discounted Amount" numeric sortable>
          {{ props.row.discounted_amount | currency('₱') }}
        </b-table-column>

        <b-table-column field="amount" label="Amount" numeric sortable>
          {{ props.row.amount | currency('₱') }}
        </b-table-column>
      </template>

      <template slot="detail" slot-scope="props">
        <b-table striped
          :data="props.row.products"
          :mobile-cards="false"
          :hoverable="true">

          <template slot-scope="props">
            <b-table-column field="code" label="UPC" sortable>
              {{ props.row.upc }}
            </b-table-column>

            <b-table-column field="name" label="Name" sortable>
              {{ props.row.name }}
            </b-table-column>

            <b-table-column field="price" label="Price" numeric sortable>
              {{ props.row.price | currency('₱')  }}
            </b-table-column>

            <b-table-column field="quantity" label="Quantity" numeric sortable>
              {{ props.row.quantity }}
            </b-table-column>

            <b-table-column field="subtotal" label="Subtotal" numeric sortable>
              {{ props.row.subtotal | currency('₱') }}
            </b-table-column>
          </template>
        </b-table>
      </template>
    </b-table>
  </section>
</template>

<style lang="scss" scoped>
  /deep/ tr.detail > td {
    padding: 0;

    & > .detail-container {
      padding: 0 !important;

      td {
        font-size: 14px;
      }
    }
  }
</style>

<script>
  import person from '@admin/partials/users/person'
  import tabs from '@admin/partials/reports/tabs'

  export default {
    components: { person, tabs },

    inject: ['$report'],

    data () {
      return {
        query: {},
        result: [],

        isLoading: false
      }
    },

    mounted () {
      this.$root.setPageTitle('Purchase History')

      this.loadData()
    },

    methods: {
      search () {
        this.loadData()
      },

      loadData () {
        this.isLoading = true

        this.$report.getPurchases(this.query)
          .then(response => this.result = response.data)
          .finally(() => this.isLoading = false)
      }
    }
  }
</script>
