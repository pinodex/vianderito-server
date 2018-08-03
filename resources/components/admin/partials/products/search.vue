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
      <label class="label">Manufacturer</label>

      <b-autocomplete
        v-model="manufacturer"
        placeholder="Search for manufacturer"
        field="name"
        
        :data="manufacturers"
        :loading="isManufacturersLoading"
        :open-on-focus="true"
        @input="searchManufacturer"
        @select="option => query.manufacturer_id = option.id">
        
        <template slot="empty">No results for {{ manufacturer }}</template>
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
    inject: ['$product', '$manufacturer', '$category'],

    props: {
      query: {
        type: Object,
        default: {}
      }
    },

    data () {
      return {
        manufacturer: '',
        category: '',

        manufacturers: [],
        categories: [],

        isManufacturersLoading: false,
        isCategoriesLoading: false
      }
    },

    mounted () {
      this.$category.get()
        .then(response => this.categories = response.data.data)

      this.$manufacturer.get()
        .then(response => this.manufacturers = response.data.data)
    },

    updated () {
      if (this.query.manufacturer_id && !this.manufacturer) {
        this.$manufacturer.fetch(this.query.manufacturer_id)
          .then(response => this.manufacturer = response.data.name)
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

      searchManufacturer: debounce(function () {
        this.manufacturers = []
        this.isManufacturersLoading = true

        let name = this.manufacturer

        this.$manufacturer.get({ name })
          .then(response => {
            this.manufacturers = response.data.data

            this.isManufacturersLoading = false
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
