<template>
  <div class="field-department has-contents-below">
    <div class="field">
      <label class="label">ID</label>

      <div class="control">
        <input class="input" type="number" required
          :class="{ 'is-danger': errors.eid }"
          v-model="model.eid" />
      </div>

      <p class="help is-danger" v-for="message in errors.eid">{{ message }}</p>
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

        @blur="validateProduct"
        @input="searchProduct"
        @select="option => model.product_id = option.id">
        
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

      <p class="help is-danger" v-for="message in errors.product_id">{{ message }}</p>
    </div>

    <div class="field">
      <label class="label">Quantity</label>

      <div class="control">
        <input class="input" type="number" required
          :class="{ 'is-danger': errors.quantity }"
          v-model.number="model.quantity" />
      </div>

      <p class="help is-danger" v-for="message in errors.quantity">{{ message }}</p>
    </div>

    <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label">Cost</label>

          <div class="control">
            <input class="input" type="text" required
              :class="{ 'is-danger': errors.cost }"
              v-cleave="masks.numeral"
              v-model="maskedCost"
              @input="e => model.cost = String(e.target._vCleave.getRawValue())" />
          </div>

          <p class="help is-danger" v-for="message in errors.cost">{{ message }}</p>
        </div>
      </div>

      <div class="column">
        <div class="field">
          <label class="label">Price</label>

          <div class="control">
            <input class="input" type="text" required
              :class="{ 'is-danger': errors.price }"
              v-cleave="masks.numeral"
              v-model="maskedPrice"
              @input="e => model.price = String(e.target._vCleave.getRawValue())" />
          </div>

          <p class="help is-danger" v-for="message in errors.price">{{ message }}</p>
        </div>
      </div>
    </div>

    <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label">Batch Date</label>

          <b-datepicker inline
            icon="calendar"
            placeholder="Click to select date"
            v-model="dates.batch_date"
            :date-formatter="formatDate"
            :date-parser="parseDate"
            :max-date="batchDateMax" />

          <p class="help is-danger" v-for="message in errors.batch_date">{{ message }}</p>
        </div>
      </div>

      <div class="column">
        <div class="field">
          <label class="label">Expiration Date</label>

          <b-datepicker inline
            icon="calendar"
            placeholder="Click to select date"
            v-model="dates.expiration_date"
            :date-formatter="formatDate"
            :date-parser="parseDate"
            :min-date="expirationDateMin" />

          <p class="help is-danger" v-for="message in errors.expiration_date">{{ message }}</p>
        </div>
      </div>
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
  import moment from 'moment'
  
  import cleave from '@directives/cleave'
  import numeral from '@masks/numeral'

  const PRODUCT_RELATIONS = 'category,supplier'

  export default {
    inject: ['$product'],

    props: ['model', 'errors'],

    directives: { cleave },

    data () {
      return {
        product: '',
        products: [],
        allProducts: [],

        isProductsLoading: false,

        maskedPrice: '',
        maskedCost: '',

        dates: {
          batch_date: null,
          expiration_date: null
        },

        masks: { numeral }
      }
    },

    computed: {
      batchDateMax () {
        return moment().toDate()
      },

      expirationDateMin () {
        if (this.dates.batch_date) {
          return moment(this.dates.batch_date)
            .add(1, 'days')
            .toDate()
        }

        return moment().toDate()
      }
    },

    mounted () {
      this.$product.get({ with: PRODUCT_RELATIONS })
        .then(response => {
          this.products = response.data.data
          this.allProducts = response.data.data
        })

      if (this.model.batch_date) {
        this.dates.batch_date = this.parseDate(this.model.batch_date)
      }

      if (this.model.expiration_date) {
        this.dates.expiration_date = this.parseDate(this.model.expiration_date)
      }

      if (this.model.price) {
        this.maskedPrice = this.model.price
        this.model.price = String(this.model.price)
      }

      if (this.model.cost) {
        this.maskedCost = this.model.cost
        this.model.cost = String(this.model.cost)
      }
    },

    updated () {
      if (this.model.product_id && !this.product) {
        this.$product.fetch(this.model.product_id, { with: PRODUCT_RELATIONS })
          .then(response => this.product = response.data.name)
      }
    },

    watch: {
      dates: {
        handler (values) {
          for (let key in values) {
            if (!values.hasOwnProperty(key)) return

            this.model[key] = this.formatDate(values[key])
          }
        },

        deep: true
      }
    },

    methods: {
      formatDate (date) {
        return moment(date).format('YYYY-MM-DD')
      },

      parseDate (date) {
        return moment(date).toDate()
      },

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

      validateProduct () {
        let product = this.allProducts.find(p => p.name == this.product)

        this.model.product_id = product ? product.id : null
      }
    }
  }
</script>
