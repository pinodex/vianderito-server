<template>
  <b-table paginated striped backend-pagination
    :data="result.data"
    :total="result.total"
    :per-page="result.per_page"
    :loading="isLoading"
    :mobile-cards="false"

    @page-change="onPageChange">

    <template slot-scope="props">
      <b-table-column label="Action">{{ props.row.description }}</b-table-column>
      <b-table-column label="IP Address">{{ props.row.ip_address }}</b-table-column>
      <b-table-column label="Browser">{{ props.row.browser }}</b-table-column>
      <b-table-column label="Date &amp; Time">{{ new Date(props.row.created_at).toLocaleString() }}</b-table-column>
    </template>
  </b-table>
</template>

<script>
  export default {
    inject: ['$account'],
    props: ['id'],

    data () {
      return {
        result: [],
        query: {},

        isLoading: false
      }
    },

    mounted () {
      this.refresh()
    },

    methods: {
      refresh () {
        this.isLoading = true

        this.$account.getLogs(this.id)
          .then(response => this.result = response.data)
          .finally(() => this.isLoading = false)
      },

      onPageChange (page) {
        this.isLoading = true

        this.$account.getLogs(this.id, { page })
          .then(response => this.result = response.data)
          .finally(() => this.isLoading = false)
      }
    }
  }
</script>
