<template>
  <div class="columns">
    <div class="column is-4">
      <div class="field-group has-contents-below">
        <label class="label">Code</label>

        <div class="field has-addons">
          <div class="control is-expanded">
            <input class="input" type="text" autocomplete="code" required
              :class="{ 'is-danger': errors.code }"
              v-model="model.code" />
          </div>

          <p class="control">
            <button type="button" class="button" @click="generateCode()">Generate</button>
          </p>
        </div>
        <p class="help is-danger" v-for="message in errors.name">{{ message }}</p>

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
          <label class="label">Discount Type</label>

          <div class="block">
            <b-radio v-model="model.discount_type" native-value="price" required>
              Price
            </b-radio>

            <b-radio v-model="model.discount_type" native-value="percentage" required>
              Percentage
            </b-radio>
          </div>
        </div>

        <div class="field" v-if="model.discount_type == 'price'">
          <label class="label">Price</label>

          <div class="control">
            <input class="input" type="number" min="0.00" step=".01" required
              :class="{ 'is-danger': errors.discount_price }"
              v-model="model.discount_price" />
          </div>

          <p class="help is-danger" v-for="message in errors.discount_price">{{ message }}</p>
        </div>

        <div class="field" v-if="model.discount_type == 'percentage'">
          <label class="label">Percentage</label>

          <div class="control">
            <input class="input" type="number" step=".01" min="0" max="100" required
              :class="{ 'is-danger': errors.discount_percentage }"
              v-model="model.discount_percentage" />
          </div>

          <p class="help is-danger" v-for="message in errors.discount_percentage">{{ message }}</p>
        </div>

        <div class="columns">
          <div class="column">
            <div class="field">
              <label class="label">Minimum Purchase Amount <small>(optional)</small></label>

              <div class="control">
                <input class="input" type="text"
                  :class="{ 'is-danger': errors.discount_floor_price }"
                  v-cleave="masks.numeral"
                  v-model="maskedFloorPrice"
                  @input="e => model.discount_floor_price = e.target._vCleave.getRawValue()" />
              </div>

              <p class="help is-danger" v-for="message in errors.discount_floor_price">{{ message }}</p>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label class="label">Maximum Purchase Amount <small>(optional)</small></label>

              <div class="control">
                <input class="input" type="text"
                  :class="{ 'is-danger': errors.discount_ceiling_price }"
                  v-cleave="masks.numeral"
                  v-model="maskedCeilingPrice"
                  @input="e => model.discount_ceiling_price = e.target._vCleave.getRawValue()" />
              </div>

              <p class="help is-danger" v-for="message in errors.discount_ceiling_price">{{ message }}</p>
            </div>
          </div>
        </div>

        <div class="field">
          <label class="label">Validity Start Date</label>

          <datetime
            input-class="input"
            type="datetime"
            v-model="dates.validity_start"
            :use12-hour="true"
            :min-datetime="now"
            :max-datetime="dates.validity_end" />

          <p class="help is-danger" v-for="message in errors.validity_start">{{ message }}</p>
        </div>

        <div class="field">
          <label class="label">Validity End</label>

          <datetime
            input-class="input"
            type="datetime"
            v-model="dates.validity_end"
            :use12-hour="true"
            :min-datetime="dates.validity_start" />

          <p class="help is-danger" v-for="message in errors.validity_end">{{ message }}</p>
        </div>
      </div>
    </div>

    <div class="column">
      <p class="is-size-6 has-contents-below">Apply coupon for the following:</p>

      <b-tabs type="is-boxed" expanded>
        <b-tab-item label="Products" icon="box">
          <productselector v-model="model.selections.products"></productselector>
        </b-tab-item>
        
        <b-tab-item label="Suppliers" icon="industry">
          <supplierselector v-model="model.selections.suppliers"></supplierselector>
        </b-tab-item>
        
        <b-tab-item label="Categories" icon="layer-group">
          <categoryselector v-model="model.selections.categories"></categoryselector>
        </b-tab-item>
        
        <b-tab-item label="Inventories" icon="boxes">
          <inventoryselector v-model="model.selections.inventories"></inventoryselector>
        </b-tab-item>
      </b-tabs>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .field-label {
    flex-grow: 2;
  }

  /deep/ .tab-content {
    padding: 0;
    
    background: #fff;
    border: 1px solid #dbdbdb;
    border-top: none;

    border-radius: 0 0 4px 4px;

    min-height: 50vh;
  }
</style>

<script>
  import debounce from 'debounce'
  import moment from 'moment'

  import cleave from '@directives/cleave'
  import numeral from '@masks/numeral'

  import productselector from './selectors/product'
  import supplierselector from './selectors/supplier'
  import categoryselector from './selectors/category'
  import inventoryselector from './selectors/inventory'

  export default {
    components: { productselector,
      supplierselector, categoryselector, inventoryselector },

    directives: { cleave },

    props: ['model', 'errors'],

    data () {
      return {
        now: null,

        dates: {
          validity_start: null,
          validity_end: null
        },

        masks: { numeral },

        maskedFloorPrice: '',
        maskedCeilingPrice: '',

        discountType: 'price'
      }
    },

    mounted () {
      this.now = (new Date()).toISOString()

      if (this.model.validity_start) {
        this.dates.validity_start = this.parseDate(this.model.validity_start)
      }

      if (this.model.validity_end) {
        this.dates.validity_end = this.parseDate(this.model.validity_end)
      }

      if (this.model.discount_ceiling_price) {
        this.maskedCeilingPrice = this.model.discount_ceiling_price
      }

      if (this.model.discount_floor_price) {
        this.maskedFloorPrice = this.model.discount_floor_price
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
        return moment(date).format('YYYY-MM-DD HH:mm:ss')
      },

      parseDate (date) {
        return moment(date).format('YYYY-MM-DDTHH:mm')
      },

      generateCode () {
        this.model.code = Math.random().toString(36).substring(7).toUpperCase()

        this.$forceUpdate()
      }
    }
  }
</script>
