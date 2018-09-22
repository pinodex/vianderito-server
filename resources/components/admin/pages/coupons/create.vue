<template>
  <section class="main-container">
    <div class="columns is-centered">
      <div class="column">
        <h1 class="title">Add Coupon</h1>

        <form @submit.prevent="submitRequest()">
          <editor :model="model" :errors="errors"></editor>

          <div class="field">
            <div class="control has-text-right">
              <button class="button is-primary is-rounded"
                :disabled="isFormLoading"
                :class="{ 'is-loading': isFormLoading }">

                <span class="icon is-small">
                  <i class="fa fa-save"></i>
                </span>

                <span>Add Coupon</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script>
  import editor from '@admin/partials/coupons/editor'

  export default {
    inject: ['$coupon'],
    
    components: { editor },

    data () {
      return {
        model: {
          selections: {}
        },

        errors: {},

        isFormLoading: false
      }
    },

    mounted () {
      this.$root.setPageTitle('Create Coupon')
    },

    methods: {
      submitRequest () {
        this.isFormLoading = true
        this.errors = {}

        this.$coupon.create(this.model)
          .then(response => {
            this.$root.$emit('coupons:saved', this.model)

            this.$toast.open({
              message: `${this.model.code} coupon has been added`,
              type: 'is-success'
            })
            
            this.$router.push({ name: 'coupons' })
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
