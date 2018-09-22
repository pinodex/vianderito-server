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

    <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label">UPC</label>

          <div class="control">
            <input class="input" type="text" required
              :class="{ 'is-danger': errors.upc }"
              v-model="model.upc" />
          </div>

          <p class="help is-danger" v-for="message in errors.upc">{{ message }}</p>
        </div>
      </div>

      <div class="column">
        <div class="field">
          <label class="label">EPC</label>

          <div class="control">
            <button type="button" class="button is-inverted is-primary is-fullwidth" @click="manageEpcs">
              <template v-if="model.epcs.length == 0">
                Manage Product EPCs
              </template>

              <template v-else>
                Manage Product EPCs ({{ model.epcs.length }})
              </template>
            </button>
          </div>

          <p class="help is-danger" v-for="message in errors.upc">{{ message }}</p>
        </div>
      </div>
    </div>

    <div class="field">
      <label class="label">Description (optional)</label>

      <div class="control">
          <textarea class="textarea"
            v-model="model.description"
            :class="{ 'is-danger': errors.description }"></textarea>
      </div>

      <p class="help is-danger" v-for="message in errors.description">{{ message }}</p>
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
        @select="option => model.supplier_id = option.id">
        
        <template slot="empty">No results for {{ supplier }}</template>
      </b-autocomplete>

      <p class="help is-danger" v-for="message in errors.supplier_id">{{ message }}</p>
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
    inject: ['$supplier', '$category'],

    props: ['model', 'errors'],

    data () {
      return {
        supplier: '',
        category: '',

        suppliers: [],
        categories: [],

        isSuppliersLoading: false,
        isCategoriesLoading: false,

        isLoaded: false
      }
    },

    mounted () {
      this.$category.get()
        .then(response => this.categories = response.data.data)

      this.$supplier.get()
        .then(response => this.suppliers = response.data.data)
    },

    updated () {
      if (this.isLoaded)
        return

      if (this.model.supplier_id && !this.supplier) {
        this.$supplier.fetch(this.model.supplier_id)
          .then(response => this.supplier = response.data.name)
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

      manageEpcs () {
        this.$root.$emit('products:manage_epcs')
      }
    }
  }
</script>
