<template>
  <section class="main-container" v-if="model.id">
    <div class="columns is-centered">
      <div class="column">
        <h1 class="title">Edit Coupon</h1>

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
  import editor from '@admin/partials/coupons/editor'

  export default {
    inject: ['$coupon'],
    
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

      this.$coupon.fetch(id)
        .then(response => this.model = response.data)
        .then(() => loadingComponent.close())
    },

    methods: {
      submitRequest () {
        this.isFormLoading = true
        this.errors = {}

        this.$coupon.update(this.model)
          .then(response => {
            this.$root.$emit('coupons:saved', this.model)

            this.$router.push({ name: 'coupons' })
            
            this.$toast.open({
              message: `Changes to coupon ${this.model.code} has been saved`,
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
