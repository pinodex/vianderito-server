<template>
  <section>
    <form class="search-form">
      <div class="field">
        <b-autocomplete
          placeholder="Search for product"
          v-model="modelName"
          
          :data="models"
          :loading="isLoading"
          :open-on-focus="true"
          @input="search"
          @select="add">
          
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

          <template slot="empty">No results</template>
        </b-autocomplete>
      </div>
    </form>

    <b-table striped
      :data="selectedModels"
      :mobile-cards="false"
      :hoverable="true">

      <template slot-scope="props">
        <b-table-column class="is-fit">
          <figure class="image is-48x48">
            <img :src="props.row.picture.thumbnail">
          </figure>
        </b-table-column>

        <b-table-column label="Name">
          <router-link :to="{ name: 'products', query: { id: props.row.id } }">
            <p>{{ props.row.name }}</p>
            <p><small>{{ props.row.upc }}</small></p>
          </router-link>
        </b-table-column>

        <b-table-column label="Category &amp; Supplier">
          <p>
            <router-link :to="{ name: 'categories', query: { id: props.row.category.id } }">
              {{ props.row.category.name }}
            </router-link>
          </p>

          <p>
            <router-link :to="{ name: 'suppliers', query: { id: props.row.supplier.id } }">
              {{ props.row.supplier.name }}
            </router-link>
          </p>
        </b-table-column>
        
        <b-table-column label=" " class="is-fit">
          <div class="field has-addons">
            <p class="control">
              <button class="button is-small is-danger" type="button" 
                @click="remove(props.index)">
                <span class="icon">
                  <i class="fa fa-trash"></i>
                </span>
              </button>
            </p>
          </div>
        </b-table-column>
      </template>
    </b-table>
  </section>
</template>

<style type="scss" scoped>
  .search-form {
    padding: 1rem;
  }
</style>

<script>
  import debounce from 'debounce'
  
  const PRODUCT_RELATIONS = 'category,supplier'

  export default {
    inject: ['$product'],

    props: ['value'],

    data () {
      return {
        isLoading: false,
        models: [],
        modelName: '',

        selectedModels: []
      }
    },

    watch: {
      selectedModels: {
        deep: true,

        handler (value) {
          let ids = []

          this.selectedModels.forEach(model => {
            ids.push(model.id)
          })

          this.$emit('input', ids)
        }
      }
    },

    mounted () {
      this.$product.get({ with: PRODUCT_RELATIONS })
        .then(response => this.models = response.data.data)

      if (this.value && this.value.length) {
        this.$product.getById(this.value, { with: PRODUCT_RELATIONS })
          .then(response => this.selectedModels = response.data)
      }
    },

    methods: {
      add (option) {
        let existingModel = this.selectedModels.find(model => model.id == option.id)

        if (existingModel) {
          this.$snackbar.open('Selected item already added')

          return
        }

        this.selectedModels.push(option)
      },

      remove (index) {
        this.selectedModels.splice(index, 1)
      },

      search: debounce(function () {
        this.models = []
        this.isLoading = true

        let name = this.modelName

        this.$product.get({ name, with: PRODUCT_RELATIONS })
          .then(response => {
            this.models = response.data.data

            this.isLoading = false
          })
      }, 500)
    }
  }
</script>
