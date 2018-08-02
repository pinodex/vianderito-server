<template>
  <section class="main-container">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <h1 class="title">Edit Product</h1>
        </div>
      </div>

      <div class="level-right"></div>
    </div>

    <div class="columns">
      <div class="column is-6">
        <form @submit.prevent="submitRequest()">
          <editor :model="model" :errors="errors"></editor>

          <div class="field">
            <div class="control">
              <button class="button is-primary is-rounded is-fullwidth"
                :disabled="isFormLoading"
                :class="{ 'is-loading': isFormLoading }">

                <span class="icon is-small">
                  <i class="fa fa-save"></i>
                </span>

                <span>Save Changes</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script>
  import editor from '@admin/partials/products/editor'

  export default {
    inject: ['$product'],
    
    components: { editor },

    data () {
      return {
        model: {},
        errors: {},

        isFormLoading: false
      }
    },

    mounted () {
      let id = this.$route.params.id

      this.$product.fetch(id)
        .then(response => this.model = response.data)
    },

    methods: {
      submitRequest () {
        this.isFormLoading = true
        this.errors = {}

        this.$product.update(this.model)
          .then(response => {
            this.$root.$emit('products:saved', this.model)

            this.$router.push({ name: 'products' })
            
            this.$toast.open({
              message: `Changes to ${this.model.name} has been saved`,
              type: 'is-success'
            })
          })
          .catch(error => {
            if (error.response.status == 422) {
              this.errors = error.response.data

              return
            }

            let message = error.response.data.message || error.response.data.error

            this.$dialog.alert({ message,
              title: 'Error',
              type: 'is-danger'
            })
          })
          .finally(() => this.isFormLoading = false)
      }
    }
  }
</script>
