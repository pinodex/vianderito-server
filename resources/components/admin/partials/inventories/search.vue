<template>
  <form @submit.prevent="search()">
    <div class="field">
      <label class="label">ID</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.eid" />
      </div>
    </div>

    <div class="field">
      <label class="label">Product</label>

      <b-autocomplete
        v-model="product"
        placeholder="Search for product"
        field="name"
        
        :data="products"
        :loading="isProductsLoading"
        :open-on-focus="true"
        @input="searchProduct"
        @select="option => query.product_id = option.id">

        <template slot-scope="props">
          <section class="media is-vcentered">
            <figure class="media-left">
              <p class="image is-48x48">
                <img :src="props.option.picture.thumbnail">
              </p>
            </figure>

            <div class="media-content">
              <h1 class="is-size-6">{{ props.option.name }} 
                {{ props.option.supplier.name ? '(' + props.option.supplier.name + ')' : '' }}
              </h1>
              <p class="is-size-7">{{ props.option.upc }}</p>
              <p class="is-size-7">
                {{ props.option.category.name ? props.option.category.name : '' }}
              </p>
            </div>
          </section>
        </template>

        <template slot="empty">No results for {{ product }}</template>
      </b-autocomplete>
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

  const PRODUCT_RELATIONS = 'category,supplier'

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
        product: '',
        supplier: '',
        category: '',

        products: [],
        suppliers: [],
        categories: [],

        isProductsLoading: false,
        isSuppliersLoading: false,
        isCategoriesLoading: false,

        isLoaded: false
      }
    },

    mounted () {
      this.$product.get({ with: PRODUCT_RELATIONS })
        .then(response => this.products = response.data.data)

      this.$category.get()
        .then(response => this.categories = response.data.data)

      this.$supplier.get()
        .then(response => this.suppliers = response.data.data)
    },

    updated () {
      if (this.isLoaded)
        return

      if (this.query.product_id && !this.product) {
        this.$product.fetch(this.query.product_id, { with: PRODUCT_RELATIONS })
          .then(response => this.product = response.data.name)
      }

      if (this.query.supplier_id && !this.supplier) {
        this.$supplier.fetch(this.query.supplier_id)
          .then(response => this.supplier = response.data.name)
      }

      if (this.query.category_id && !this.category) {
        this.$category.fetch(this.query.category_id)
          .then(response => this.category = response.data.name)
      }

      this.isLoaded = true
    },

    methods: {
      searchProduct: debounce(function () {
        this.products = []
        this.isProductsLoading = true

        let name = this.product

        this.$product.get({ name, with: PRODUCT_RELATIONS })
          .then(response => {
            this.products = response.data.data

            this.isProductsLoading = false
          })
      }, 500),

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
        this.$root.$emit('inventories:query:clear')
      },

      search () {
        this.$root.$emit('inventories:query', this.query)
      }
    }
  }  
</script>
