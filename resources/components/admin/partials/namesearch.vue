<template>
  <p class="control has-icons-left">
    <span class="icon">
      <i class="fa fa-search"></i>
    </span>

    <input type="input" class="input is-rounded" placeholder="Search for name..."
      v-model="query[field]"
      @input="search" />
  </p>
</template>

<script>
  import debounce from 'debounce'

  export default {
    props: {
      query: {
        type: Object,
        default: {}
      },

      field: {
        type: String,
        default: 'name'
      },

      module: {
        type: String
      }
    },

    methods: {
      clear () {
        this.$root.$emit(`${this.module}:query:clear`)
      },

      search: debounce(function () {
        this.$root.$emit(`${this.module}:query`, this.query)
      }, 500)
    }
  }  
</script>
