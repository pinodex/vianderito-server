<template>
  <section class="main-container">
    <div class="columns is-centered">
      <div class="column is-6">
        <h1 class="title">Add Inventory</h1>

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

                <span>Add Inventory</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script>
  import editor from '@admin/partials/inventories/editor'

  export default {
    inject: ['$inventory'],
    
    components: { editor },

    data () {
      return {
        model: {},
        errors: {},

        picture: null,

        isFormLoading: false
      }
    },

    mounted () {
      this.$root.setPageTitle('Add Inventory')
    },

    methods: {
      imageInput (value) {
        this.picture = value[0]
      },

      fileSizeExceed () {
        this.$dialog.alert({
          title: 'Image too large',
          message: `Image file size should not exceed 2MB`
        })
      },

      submitRequest () {
        this.isFormLoading = true
        this.errors = {}

        this.$inventory.create(this.model)
          .then(response => {
            this.$root.$emit('inventories:saved', this.model)

            this.$toast.open({
              message: `Product inventory has been added`,
              type: 'is-success'
            })
            
            this.$router.push({ name: 'inventories' })
          })
          .catch(error => {
            if (error.response.status == 422) {
              this.errors = error.response.data.errors

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
