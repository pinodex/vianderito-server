<template>
  <form @submit.prevent="submitRequest()">
    <editor :model="model" :errors="errors" />

    <div class="level">
      <div class="level-left"></div>
      
      <div class="level-right">
        <div class="field">
          <div class="control">
            <button class="button is-fullwidth is-primary"
              :disabled="isFormLoading"
              :class="{ 'is-loading': isFormLoading }">

              <span class="icon is-small">
                <i class="fa fa-save"></i>
              </span>

              <span>Save Changes</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
  import editor from './editor'

  export default {
    inject: ['$inventory'],

    components: { editor },

    props: {
      id: {
        type: String
      },

      model: {
        type: Object
      }
    },

    data () {
      return {
        isFormLoading: false,
        errors: {}
      }
    },

    methods: {
      submitRequest () {
        this.isFormLoading = true
        this.errors = {}

        this.$inventory.updateLoss(this.id, this.model.id, this.model)
          .then(response => {
            this.$root.$emit('inventory_losses:saved', this.model)

            this.$parent.close()
            
            this.$toast.open({
              message: `Changes to the inventory loss has been saved`,
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
      },

      close () {
        this.$parent.close()
      }
    }
  }
</script>
