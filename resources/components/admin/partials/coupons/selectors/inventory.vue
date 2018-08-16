<template>
  <section>
    <form class="search-form">
      <div class="field">
        <b-autocomplete
          placeholder="Search for inventory number"
          v-model="modelName"
          
          :data="models"
          :loading="isLoading"
          :open-on-focus="true"
          @input="search"
          @select="add">
          
          <template slot-scope="props">
            <p>{{ props.option.product.name }}</p>
            <p>ID: {{ props.option.eid }}</p>
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
        <b-table-column label="ID">
          {{ props.row.eid }}
        </b-table-column>

        <b-table-column label="Product">
          <section class="media is-vcentered">
            <figure class="media-left">
              <p class="image is-48x48">
                <img :src="props.row.product.picture.thumbnail">
              </p>
            </figure>

            <router-link class="media-content"
              :to="{ name: 'products', query: { id: props.row.product.id } }">
              <h1 class="is-size-6">{{ props.row.product.name }} </h1>

              <p class="is-size-7">
                {{ props.row.product.upc }}
              </p>
            </router-link>
          </section>
        </b-table-column>

        <b-table-column label="Batch Date">
          {{ props.row.batch_date }}
        </b-table-column>

        <b-table-column label="Expiration">
          {{ props.row.expiration_date }}
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

  export default {
    inject: ['$inventory'],

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
      this.$inventory.get()
        .then(response => this.models = response.data.data)

      if (this.value && this.value.length) {
        this.$inventory.getById(this.value)
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

        let eid = this.modelName

        this.$inventory.get({ eid })
          .then(response => {
            this.models = response.data.data

            this.isLoading = false
          })
      }, 500)
    }
  }
</script>
