<template>
  <form @submit.prevent="submitRequest()">
    <editor :model="model" :errors="errors" />

    <div class="field">
      <div class="control">
        <button class="button is-primary is-fullwidth"
          :disabled="isFormLoading"
          :class="{ 'is-loading': isFormLoading }">

          <span class="icon is-small">
            <i class="fa fa-save"></i>
          </span>

          <span>Create Department</span>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import editor from './editor'

  export default {
    inject: ['$department'],
    components: { editor },

    data () {
      return {
        isFormLoading: false,

        model: {},
        errors: {}
      }
    },

    methods: {
      submitRequest () {
        this.isFormLoading = true
        this.errors = {}

        this.$department.create(this.model)
          .then(response => {
            this.$root.$emit('departments:saved', this.model)

            this.$toast.open({
              message: `${this.model.name} department has been added`,
              type: 'is-success'
            })

            this.close()
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
