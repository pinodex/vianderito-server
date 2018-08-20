<template>
  <div class="columns">
    <div class="column is-4">
      <div class="field-group has-contents-below">
        <div class="field">
          <label class="label">Code</label>

          <div class="control">
            <input class="input" type="text" autocomplete="code" required
              :class="{ 'is-danger': errors.code }"
              v-model="model.code" />
          </div>

          <p class="help is-danger" v-for="message in errors.name">{{ message }}</p>
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
              <label class="label">Minimum Amount</label>

              <div class="control">
                <input class="input" type="number" min="0.00" step=".01" required
                  :class="{ 'is-danger': errors.discount_floor_price }"
                  v-model="model.discount_floor_price" />
              </div>

              <p class="help is-danger" v-for="message in errors.discount_floor_price">{{ message }}</p>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label class="label">Maximum Amount</label>

              <div class="control">
                <input class="input" type="number" min="0.00" step=".01" required
                  :class="{ 'is-danger': errors.discount_ceiling_price }"
                  v-model="model.discount_ceiling_price" />
              </div>

              <p class="help is-danger" v-for="message in errors.discount_ceiling_price">{{ message }}</p>
            </div>
          </div>
        </div>

        <div class="field">
          <label class="label">Validity Start Date</label>

          <input class="input" type="datetime-local" required
              :max="dates.validity_end"
              :class="{ 'is-danger': errors.validity_start }"
              v-model="dates.validity_start" />

          <p class="help is-danger" v-for="message in errors.validity_start">{{ message }}</p>
        </div>

        <div class="field">
          <label class="label">Validity End</label>

          <input class="input" type="datetime-local" required
              :min="dates.validity_start"
              :class="{ 'is-danger': errors.validity_end }"
              v-model="dates.validity_end" />

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
        
        <b-tab-item label="Manufacturers" icon="industry">
          <manufacturerselector v-model="model.selections.manufacturers"></manufacturerselector>
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

  import productselector from './selectors/product'
  import manufacturerselector from './selectors/manufacturer'
  import categoryselector from './selectors/category'
  import inventoryselector from './selectors/inventory'

  export default {
    components: { productselector,
      manufacturerselector, categoryselector, inventoryselector },

    props: ['model', 'errors'],

    data () {
      return {
        dates: {
          validity_start: null,
          validity_end: null
        },

        discountType: 'price'
      }
    },

    mounted () {
      if (this.model.validity_start) {
        this.dates.validity_start = this.parseDate(this.model.validity_start)
      }

      if (this.model.validity_end) {
        this.dates.validity_end = this.parseDate(this.model.validity_end)
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
      }
    }
  }
</script>