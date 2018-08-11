<template>
  <div class="field-group has-contents-below">
    <div class="field">
      <label class="label">Name</label>

      <div class="control">
        <input class="input" type="text" autocomplete="name" required
          :class="{ 'is-danger': errors.name }"
          v-model="model.name" />
      </div>

      <p class="help is-danger" v-for="message in errors.name">{{ message }}</p>
    </div>

    <div class="field">
      <label class="label">UPC</label>

      <div class="control">
        <input class="input" type="text" required
          :class="{ 'is-danger': errors.upc }"
          v-model="model.upc" />
      </div>

      <p class="help is-danger" v-for="message in errors.upc">{{ message }}</p>
    </div>

    <div class="field">
      <label class="label">Description</label>

      <div class="control">
          <textarea class="textarea"
            v-model="model.description"
            :class="{ 'is-danger': errors.description }"></textarea>
      </div>

      <p class="help is-danger" v-for="message in errors.description">{{ message }}</p>
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
        @select="option => model.manufacturer_id = option.id">
        
        <template slot="empty">No results for {{ manufacturer }}</template>
      </b-autocomplete>

      <p class="help is-danger" v-for="message in errors.manufacturer_id">{{ message }}</p>
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
        @select="option => model.category_id = option.id">
        
        <template slot="empty">No results for {{ category }}</template>
      </b-autocomplete>

      <p class="help is-danger" v-for="message in errors.category_id">{{ message }}</p>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .field-label {
    flex-grow: 2;
  }
</style>

<script>
  import debounce from 'debounce'

  export default {
    inject: ['$manufacturer', '$category'],

    props: ['model', 'errors'],

    data () {
      return {
        manufacturer: '',
        category: '',

        manufacturers: [],
        categories: [],

        isManufacturersLoading: false,
        isCategoriesLoading: false,

        isLoaded: false
      }
    },

    mounted () {
      this.$category.get()
        .then(response => this.categories = response.data.data)

      this.$manufacturer.get()
        .then(response => this.manufacturers = response.data.data)
    },

    updated () {
      if (this.isLoaded)
        return

      if (this.model.manufacturer_id && !this.manufacturer) {
        this.$manufacturer.fetch(this.model.manufacturer_id)
          .then(response => this.manufacturer = response.data.name)
      }

      if (this.model.category_id && !this.category) {
        this.$category.fetch(this.model.category_id)
          .then(response => this.category = response.data.name)
      }

      this.isLoaded = true
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
      }, 500)
    }
  }
</script>
