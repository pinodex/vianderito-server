<template>
  <div class="field-group has-contents-below">
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
                {{ props.option.manufacturer.name ? '(' + props.option.manufacturer.name + ')' : '' }}
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
      <label class="label">Stocks</label>

      <div class="control">
        <input class="input" type="number" required
          :class="{ 'is-danger': errors.stocks }"
          v-model="model.stocks" />
      </div>

      <p class="help is-danger" v-for="message in errors.stocks">{{ message }}</p>
    </div>

    <div class="field">
      <label class="label">Price</label>

      <div class="control">
        <input class="input" type="number" step=".01" required
          :class="{ 'is-danger': errors.price }"
          v-model="model.price" />
      </div>

      <p class="help is-danger" v-for="message in errors.price">{{ message }}</p>
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
            :max-date="dates.expiration_date" />

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
            :min-date="dates.batch_date ? dates.batch_date : beforeToday" />

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

  const PRODUCT_RELATIONS = 'category,manufacturer'

  export default {
    inject: ['$product'],

    props: ['model', 'errors'],

    data () {
      return {
        product: '',
        products: [],
        isProductsLoading: false,
        beforeToday: new Date(Date.now() - 86400000),

        dates: {
          batch_date: null,
          expiration_date: null
        }
      }
    },

    mounted () {
      this.$product.get({ with: PRODUCT_RELATIONS })
        .then(response => this.products = response.data.data)

      if (this.model.batch_date) {
        this.dates.batch_date = this.parseDate(this.model.batch_date)
      }

      if (this.model.expiration_date) {
        this.dates.expiration_date = this.parseDate(this.model.expiration_date)
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
      }, 500)
    }
  }
</script>