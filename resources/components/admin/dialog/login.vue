<template>
  <div class="box">
    <transition name="fade" mode="out-in">
      <form key="login" @submit.prevent="login()" v-if="!isAssist">
        <div class="has-text-centered">
          <img src="/assets/img/vianderito-128.png" alt="Logo" />
        </div>

        <div class="field">
          <p class="help is-danger has-text-centered"
            v-if="hasError">{{ errorMessage }}</p>
        </div>

        <div class="field">
          <div class="control">
            <input class="input" type="text" name="id"
              autocomplete="username" placeholder="Username or Email"
              v-model="credentials.id"
              :class="{ 'is-danger': hasError }"
              :disabled="isLoading">
          </div>
        </div>

        <div class="field">
          <div class="control">
            <input class="input" type="password" name="password"
              autocomplete="current-password" placeholder="Password"
              v-model="credentials.password"
              :class="{ 'is-danger': hasError }"
              :disabled="isLoading">
          </div>
        </div>

        <div class="field">
          <div class="control">
            <button type="submit" class="button is-fullwidth is-info"
              :class="{ 'is-loading': isLoading }"
              v-show="!isLoggedIn">Login</button>

            <button type="button" class="button is-fullwidth is-success" disabled
              v-show="isLoggedIn">Redirecting&hellip;</button>
          </div>
        </div>

        <p class="has-text-centered">
          <a @click.prevent="showLoginAssistant()">Forgot Password</a>
        </p>
      </form>

      <form key="assist" @submit.prevent="submitAssistant()" v-if="isAssist">
        <p class="has-contents-below">
          <a @click.prevent="hideLoginAssistant()">
            <span class="icon is-small">
              <i class="fa fa-arrow-left"></i>
            </span>

            <span>Back to login</span>
          </a>
        </p>

        <h2 class="subtitle">Forgot Password</h2>

        <template v-if="!isAssistCompleted">
          <div class="notification">
            <p>Enter the email address used in your account.</p>
          </div>

          <div class="field">
            <div class="control">
              <input class="input" type="text" name="email"
                autocomplete="email" placeholder="Email Address"
                v-model="assist.email">
            </div>
          </div>

          <div class="field">
            <div class="control">
              <button type="submit" class="button is-fullwidth"
                :class="{ 'is-loading': isLoading }">Reset Password</button>
            </div>
          </div>
        </template>

        <template v-else>
          <div class="notification is-success">
            <p>Please check your email for instructions on resetting your password.</p>
          </div>
        </template>
      </form>
    </transition>
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
</style>

<script>
  export default {
    inject: ['$auth'],

    data () {
      return {
        credentials: {
          id: null,
          password: null
        },

        assist: {
          email: null
        },

        isLoading: false,
        isLoggedIn: false,
        hasError: false,

        isAssist: false,
        isAssistCompleted: false
      }
    },

    methods: {
      login () {
        this.isLoading = true

        this.$auth.login(this.credentials)
          .then(response => {
            this.isLoggedIn = true
            this.hasError = false

            location.reload()
          })
          .catch(error => {
            this.isLoading = false
            this.hasError = true

            this.errorMessage = error.response.data.error
          })
      },

      submitAssistant () {
        this.isLoading = true

        this.$auth.requestPasswordReset(this.assist)
          .then(response => this.isAssistCompleted = true)
          .finally(() => this.isLoading = false)
      },

      showLoginAssistant() {
        this.isAssist = true
      },

      hideLoginAssistant() {
        this.isAssist = false
        this.isAssistCompleted = false

        this.assist.email = null
      }
    }
  }
</script>
