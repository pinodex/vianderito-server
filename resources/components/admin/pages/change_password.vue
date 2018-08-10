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

          <meteredpassword label="New Password"
            @input="newPasswordInput" />

          <div class="field">
            <div class="control">
              <button type="submit" class="button is-primary is-fullwidth"
                :class="{ 'is-loading': isLoading }">
                Change Password
              </button>
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
  import meteredpassword from '@admin/partials/metered-password'

  export default {
    components: { meteredpassword },

    inject: ['$auth'],

    data () {
      return {
        model: {
          current_password: '',
          new_password: ''
        },

        isLoading: false,
        message: ''
      }
    },

    methods: {
      newPasswordInput (value) {
        this.model.new_password = value
      },

      submit () {
        this.isLoading = true

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

            this.message = error.response.data.message
          })
      }
    }
  }
</script>
