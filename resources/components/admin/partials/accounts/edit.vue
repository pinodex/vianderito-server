<template>
  <form @submit.prevent="submitRequest()">
    <editor :model="model" :errors="errors"
      @passwordResetRequest="resetPassword"
      @fileSizeExceed="fileSizeExceed"
      @filesChange="filesChange" />

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
    inject: ['$account'],
    components: { editor },
    props: ['model'],

    data () {
      return {
        isFormLoading: false,

        files: [],
        errors: {}
      }
    },

    methods: {
      filesChange (value) {
        this.files = value
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

        this.$account.update(this.model)
          .then(response => {
            if (this.files.length > 0) {
              return this.$account.setAvatar(response.data.id, this.files[0])
            }

            return response
          })
          .then(response => {
            this.$root.$emit('accounts:saved', this.model)

            this.$parent.close()
            
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
      },

      resetPassword () {
        this.$dialog.confirm({
          title: 'Password Reset',

          message: `A new password will be set for ${this.model.name}. Confirm?`,
          
          onConfirm: () => {
            const loadingComponent = this.$loading.open()

            this.$account.resetPassword(this.model.id)
              .then(response => {
                let title = 'Password Reset',
                    message = `New password for ${this.model.name}<br /><br />` +
                      `Username: <strong>${this.model.username}</strong><br />` +
                      `Password: <strong>${response.data.generated_password}</strong>`
                
                this.$parent.close()
                this.$dialog.alert({ title, message })
              })
              .catch(error => {
                let message = error.response.data.message || error.response.data.error

                this.$dialog.alert({ message,
                  title: 'Error',
                  type: 'is-danger'
                })
              })
              .finally(() => {
                loadingComponent.close()
              })
          }
        })
      },

      close () {
        this.$parent.close()
      }
    }
  }
</script>
