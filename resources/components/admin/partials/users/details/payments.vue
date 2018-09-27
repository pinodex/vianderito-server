<template>
  <b-table paginated striped backend-pagination
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

      <b-table-column field="status" label="Status" sortable>
        {{ props.row.status }}
      </b-table-column>

      <b-table-column field="amount" label="Amount" numeric sortable>
        {{ props.row.amount | currency('₱') }}
      </b-table-column>

      <b-table-column field="gateway_id" label="Gateway ID">
        {{ props.row.gateway_id }}
      </b-table-column>
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

        this.$user.getPayments(this.user.id, { page })
          .then(response => this.result = response.data)
          .finally(() => this.isLoading = false)
      }
    }
  }
</script>
