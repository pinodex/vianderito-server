<template>
  <div class="box">
    <template v-if="isCompleted">
      <div class="notification is-success">
        <p>You have successfully set a new password for your account.</p>
      </div>

      <p>
        <a href="/admin/login" class="button is-fullwidth">
          <span class="icon is-small">
            <i class="fa fa-arrow-left"></i>
          </span>

          <span>Back to login</span>
        </a>
      </p>
    </template>

    <form @submit.prevent="resetPassword()" v-else>
      <p class="has-contents-below">
        <a href="/admin/login">
          <span class="icon is-small">
            <i class="fa fa-arrow-left"></i>
          </span>

          <span>Back to login</span>
        </a>
      </p>

      <p class="is-size-5">Password Reset</p>
      <p class="has-contents-below">Set a new password for your account.</p>

      <div class="notification is-danger" v-if="errorMessage">
        <p>{{ errorMessage }}</p>
      </div>

      <div class="field">
        <div class="control">
          <input class="input" type="password" placeholder="New Password"
            :class="{ 'is-danger': errors.new_password }"
            v-model="credentials.new_password">
        </div>

        <p class="help is-danger" v-for="message in errors.new_password">{{ message }}</p>
      </div>

      <div class="field">
        <div class="control">
          <input class="input" type="password" placeholder="Confirm New Password"
            v-model="passwordConfirm"
            :class="{ 'is-danger': isNotMatch }">
        </div>

        <p class="help is-danger" v-if="isNotMatch">Password does not match</p>
      </div>

      <div class="password-meter-container has-contents-below">
        <passwordMeter :password="credentials.new_password" />
      </div>

      <div class="field">
        <div class="control">
          <button type="submit" class="button is-fullwidth is-info"
            :class="{ 'is-loading': isLoading }"
            :disabled="passwordConfirm.length && isNotMatch">Set Password</button>
        </div>
      </div>
    </form>
  </div>
</template>

<style lang="scss" scoped>
  .box {
    border-radius: 3px;
    padding: 50px;
  }

  .input, .button {
    border-radius: 50px;
    box-shadow: none;

    padding: 20px;
    line-height: 0;
  }

  .password-meter-container {
    min-height: 35px;
  }
</style>

<script>
  import passwordMeter from '@admin/partials/password-meter'

  export default {
    components: { passwordMeter },

    inject: ['$auth'],

    props: {
      id: {
        type: String
      }
    },

    data () {
      return {
        credentials: {
          new_password: ''
        },

        errors: {},

        isNotMatch: false,
        passwordConfirm: '',

        isLoading: false,
        isCompleted: false,

        errorMessage: null
      }
    },

    mounted () {
      this.$watch(vm => [this.credentials.new_password,
        this.passwordConfirm].join(), val => {

        if (this.passwordConfirm &&
          this.credentials.new_password != this.passwordConfirm) {
          
          this.isNotMatch = true

          return
        }

        this.isNotMatch = false
      })
    },

    methods: {
      resetPassword () {
        this.isLoading = true
        
        this.errorMessage = ''
        this.errors = {}

        this.credentials.token = this.$route.query.token

        this.$auth.resetPassword(this.id, this.credentials)
          .then(response => {
            this.isCompleted = true
          })
          .catch(error => {
            if (error.response.status == 422)
              this.errors = error.response.data.errors

            if (error.response.status != 422)
              this.errorMessage = error.response.data.message
          })
          .finally(() => this.isLoading = false)
      }
    }
  }
</script>
