<template>
  <section>
    <form class="search-form">
      <div class="field">
        <b-autocomplete
          placeholder="Search for supplier"
          v-model="modelName"
          
          :data="models"
          :loading="isLoading"
          :open-on-focus="true"
          @input="search"
          @select="add">
          
          <template slot-scope="props">
            <p>{{ props.option.name }}</p>
            <p>{{ props.option.code }}</p>
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
        <b-table-column label="Name">
          <router-link :to="{ name: 'suppliers', query: { id: props.row.id } }">
            <p>{{ props.row.name }}</p>
          </router-link>
        </b-table-column>

        <b-table-column label="Code">
          <p>{{ props.row.code }}</p>
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
    inject: ['$supplier'],

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
      this.$supplier.get()
        .then(response => this.models = response.data.data)

      if (this.value && this.value.length) {
        this.$supplier.getById(this.value)
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

        this.$supplier.get({ name })
          .then(response => {
            this.models = response.data.data

            this.isLoading = false
          })
      }, 500)
    }
  }
</script>
