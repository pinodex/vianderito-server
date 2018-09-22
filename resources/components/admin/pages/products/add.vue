<template>
  <section class="main-container">
    <div class="columns is-centered">
      <div class="column is-6">
        <h1 class="title">Add Product</h1>

        <form @submit.prevent="submitRequest()">
          <div class="field">
            <label class="label">Picture</label>

            <div class="control">
              <productimage class="has-contents-below"
                @fileSizeExceed="fileSizeExceed"
                @input="imageInput"
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

                <span>Add Product</span>
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
        model: {
          epcs: []
        },
        
        errors: {},

        picture: null,

        isFormLoading: false,
        manageEpcsModal: false
      }
    },

    mounted () {
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

        this.$product.create(this.model)
          .then(response => {
            if (this.picture) {
              return this.$product.setPicture(response.data.id, this.picture)
            }

            return response
          })
          .then(response => {
            this.$root.$emit('products:saved', this.model)

            this.$toast.open({
              message: `${this.model.name} product has been added`,
              type: 'is-success'
            })
            
            this.$router.push({ name: 'products' })
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
