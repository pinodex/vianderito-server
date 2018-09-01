<template>
  <section class="main-container">
    <div class="columns is-centered">
      <div class="column is-4">
        <div class="notification is-warning"
          v-if="$root.account.require_password_change">
          Please set a new password for your account
        </div>

        <h1 class="title">Change Password</h1>

        <div class="notification is-danger" v-if="message">
          {{ message }}
        </div>

        <form @submit.prevent="submit" :disabled="isLoading">
          <div class="field">
            <label class="label">Current Password</label>
            
            <div class="control">
              <input class="input" type="password" v-model="model.current_password" />
            </div>
          </div>

          <div class="field">
            <label class="label">New Password</label>
            
            <div class="control">
              <input class="input" type="password" v-model="model.new_password"
                :class="{ 'is-danger': errors.new_password }" />
            </div>

            <p class="help is-danger" v-for="message in errors.new_password">{{ message }}</p>
          </div>

          <div class="password-meter-container">
            <passwordMeter :password="model.new_password" />
          </div>

          <div class="field">
            <label class="label">Confirm Password</label>
            
            <div class="control">
              <input class="input" type="password" v-model="passwordConfirm" />
            </div>

            <p class="help is-danger" v-if="isNotMatch">Password does not match</p>
          </div>

          <div class="field">
            <div class="control">
              <button type="submit" class="button is-primary is-fullwidth"
                :class="{ 'is-loading': isLoading }"
                :disabled="passwordConfirm.length && isNotMatch">Change Password</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
  @media (min-width: 1088px) {
    .main-container {
      margin-top: 100px;
    }
  }
</style>

<script>
  import passwordMeter from '@admin/partials/password-meter'

  export default {
    components: { passwordMeter },

    inject: ['$auth'],

    data () {
      return {
        model: {
          current_password: '',
          new_password: ''
        },

        errors: {},

        passwordConfirm: '',

        isLoading: false,
        isNotMatch: false,
        message: ''
      }
    },

    mounted () {
      this.$watch(vm => [this.model.new_password,
        this.passwordConfirm].join(), val => {

        if (this.passwordConfirm &&
          this.model.new_password != this.passwordConfirm) {
          
          this.isNotMatch = true

          return
        }

        this.isNotMatch = false
      })
    },

    methods: {
      newPasswordInput (value) {
        this.model.new_password = value
      },

      submit () {
        this.isLoading = true
        this.errors = {}

        this.$auth.changePassword(this.model)
          .then(response => {
            this.$toast.open({
              message: `Your password has been successfully updated`,
              type: 'is-success'
            })

            this.$root.account.require_password_change = false
            this.$router.push({ name: 'index' })
          })
          .catch(error => {
            this.isLoading = false

            if (error.response.status == 422)
              this.errors = error.response.data.errors

            if (error.response.status != 422)
              this.message = error.response.data.message
          })
      }
    }
  }
</script>
