<template>
  <div>
    <form v-show="screen == 1" @submit.prevent="submitRequest()">
      <editor :model="model" :errors="errors"
        @fileSizeExceed="fileSizeExceed"
        @filesChange="filesChange" />

      <div class="field">
        <div class="control">
          <button class="button is-primary is-fullwidth"
            :disabled="isFormLoading"
            :class="{ 'is-loading': isFormLoading }">

            <span class="icon is-small">
              <i class="fa fa-save"></i>
            </span>

            <span>Create Account</span>
          </button>
        </div>
      </div>
    </form>

    <section v-show="screen == 2">
      <div class="content">
        <h1 class="is-size-5">Account created successfully</h1>
        <p>Below are the login credentials for {{ name }}</p>

        <p>
          Username: {{ model.username }}<br />
          Password: {{ generated_password }}
        </p>

        <p class="is-size-7">
          <em>The user will be required to change password on the first login.</em>
        </p>

        <div class="has-text-right">
          <button class="button is-primary" @click="close()">Close</button>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import editor from './editor'

  export default {
    inject: ['$account'],
    components: { editor },

    data () {
      return {
        isFormLoading: false,

        model: {},
        files: [],
        errors: {},

        generated_password: '',
        screen: 1,
      }
    },

    computed: {
      name () {
        return `${this.model.first_name} ${this.model.last_name}`
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

        let generatedPassword = null

        this.$account.create(this.model)
          .then(response => {
            generatedPassword = response.data.generated_password

            if (this.files.length > 0) {
              return this.$account.setAvatar(response.data.id, this.files[0])
            }

            return response
          })
          .then(response => {
            this.$root.$emit('accounts:saved', this.model)

            this.generated_password = generatedPassword
            this.screen = 2
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
