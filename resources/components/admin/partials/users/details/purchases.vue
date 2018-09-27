<template>
  <b-table paginated striped backend-pagination detailed
    :data="result.data"
    :total="result.total"
    :per-page="result.per_page"
    :loading="isLoading"
    :mobile-cards="false"
    :hoverable="true"

    @page-change="load">

    <template slot-scope="props">
      <b-table-column field="created_at" label="Date" centered sortable>
        {{ props.row.created_at | moment('MMM DD, YYYY hh:mm A') }}
      </b-table-column>

      <b-table-column field="amount" label="Amount" numeric sortable>
        {{ props.row.amount | currency('₱') }}
      </b-table-column>

      <b-table-column field="products.length" label="Items" numeric sortable>
        {{ props.row.products.length }}
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
</template>

<style lang="scss" scoped>
  /deep/ tr.detail > td {
    padding: 0;

    & > .detail-container {
      padding: 0 !important;

      td {
        font-size: 12px;
      }
    }
  }
</style>

<script>
  export default {
    props: ['user'],

    inject: ['$user'],

    data () {
      return {
        result: {},
        isLoading: false
      }
    },

    mounted () {
      this.load()
    },

    methods: {
      load (page = 1) {
        this.isLoading = true

        this.$user.getPurchases(this.user.id, { page })
          .then(response => this.result = response.data)
          .finally(() => this.isLoading = false)
      }
    }
  }
</script>
