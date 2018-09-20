<template>
  <section class="main-container" v-if="model.id">
    <div class="columns is-centered">
      <div class="column is-6">
        <h1 class="title">Edit Product</h1>

        <form @submit.prevent="submitRequest()">
          <div class="field">
            <label class="label">Picture</label>

            <div class="control">
              <productimage class="has-contents-below"
                @fileSizeExceed="fileSizeExceed"
                @input="imageInput"
                :preview="model.picture.image"
                :file="picture" />
            </div>
          </div>

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

    <b-modal :active.sync="manageEpcsModal" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Manage EPCs</h1>

        <div class="modal-body">
          <epcs :epcs="model.epcs"></epcs>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import editor from '@admin/partials/products/editor'
  import productimage from '@admin/partials/products/image'
  import epcs from '@admin/partials/products/epcs'

  export default {
    inject: ['$product'],
    
    components: { editor, productimage, epcs },

    data () {
      return {
        model: {},
        errors: {},

        picture: null,

        isFormLoading: false,
        manageEpcsModal: false
      }
    },

    mounted () {
      const loadingComponent = this.$loading.open()

      let id = this.$route.params.id

      this.$product.fetch(id)
        .then(response => this.model = response.data)
        .then(() => loadingComponent.close())

      this.$root.$on('products:manage_epcs', () => this.manageEpcsModal = true)
    },

    beforeDestroy () {
      this.$root.$off('products:manage_epcs')
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

        this.$product.update(this.model)
          .then(response => {
            if (this.picture) {
              return this.$product.setPicture(this.model.id, this.picture)
            }

            return response
          })
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
