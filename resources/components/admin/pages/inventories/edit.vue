<template>
  <section class="main-container" v-if="model.id">
    <div class="columns is-centered">
      <div class="column is-6">
        <h1 class="title">Edit Inventory</h1>

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
      const loadingComponent = this.$loading.open()

      let id = this.$route.params.id

      this.$inventory.fetch(id)
        .then(response => this.model = response.data)
        .then(() => loadingComponent.close())
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

        this.$inventory.update(this.model)
          .then(response => {
            if (this.picture) {
              return this.$inventory.setPicture(this.model.id, this.picture)
            }

            return response
          })
          .then(response => {
            this.$root.$emit('inventories:saved', this.model)

            this.$router.push({ name: 'inventories' })
            
            this.$toast.open({
              message: `Changes to product inventory has been saved`,
              type: 'is-success'
            })
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
