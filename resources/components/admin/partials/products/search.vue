<template>
  <form @submit.prevent="search()">
    <div class="field">
      <label class="label">Name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.name" />
      </div>
    </div>

    <div class="field">
      <label class="label">UPC</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.upc" />
      </div>
    </div>

    <div class="field">
      <label class="label">Supplier</label>

      <b-autocomplete
        v-model="supplier"
        placeholder="Search for supplier"
        field="name"
        
        :data="suppliers"
        :loading="isSuppliersLoading"
        :open-on-focus="true"
        @input="searchSupplier"
        @select="option => query.supplier_id = option.id">
        
        <template slot="empty">No results for {{ supplier }}</template>
      </b-autocomplete>
    </div>

    <div class="field">
      <label class="label">Category</label>

      <b-autocomplete
        v-model="category"
        placeholder="Search for category"
        field="name"
        
        :data="categories"
        :loading="isCategoriesLoading"
        :open-on-focus="true"
        @input="searchCategory"
        @select="option => query.category_id = option.id">
        
        <template slot="empty">No results for {{ category }}</template>
      </b-autocomplete>
    </div>

    <div class="field is-grouped is-pulled-right">
      <div class="control">
        <button type="button" class="button" @click="clear()">Clear</button>
      </div>

      <div class="control">
        <button type="submit" class="button is-info">
          <span class="icon is-small">
            <i class="fa fa-search"></i>
          </span>

          <span>Search</span>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import debounce from 'debounce'

  export default {
    inject: ['$product', '$supplier', '$category'],

    props: {
      query: {
        type: Object,
        default: {}
      }
    },

    data () {
      return {
        supplier: '',
        category: '',

        suppliers: [],
        categories: [],

        isSuppliersLoading: false,
        isCategoriesLoading: false
      }
    },

    mounted () {
      this.$category.get()
        .then(response => this.categories = response.data.data)

      this.$supplier.get()
        .then(response => this.suppliers = response.data.data)
    },

    updated () {
      if (this.query.supplier_id && !this.supplier) {
        this.$supplier.fetch(this.query.supplier_id)
          .then(response => this.supplier = response.data.name)
      }

      if (this.query.category_id && !this.category) {
        this.$category.fetch(this.query.category_id)
          .then(response => this.category = response.data.name)
      }
    },

    methods: {
      searchCategory: debounce(function () {
        this.categories = []
        this.isCategoriesLoading = true

        let name = this.category

        this.$category.get({ name })
          .then(response => {
            this.categories = response.data.data

            this.isCategoriesLoading = false
          })
      }, 500),

      searchSupplier: debounce(function () {
        this.suppliers = []
        this.isSuppliersLoading = true

        let name = this.supplier

        this.$supplier.get({ name })
          .then(response => {
            this.suppliers = response.data.data

            this.isSuppliersLoading = false
          })
      }, 500),

      clear () {
        this.$root.$emit('products:query:clear')
      },

      search () {
        this.$root.$emit('products:query', this.query)
      }
    }
  }  
</script>
