<template>
  <div class="box">
    <div class="has-text-centered">
      <img src="/assets/img/vianderito-128.png" alt="Logo" />
    </div>

    <form @submit.prevent="login()">
      <div class="field">
        <p class="help is-danger has-text-centered"
          v-if="hasError">{{ errorMessage }}</p>
      </div>

      <div class="field">
        <div class="control">
          <input class="input" type="text" name="username"
            autocomplete="username" placeholder="Username"
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

        isLoading: false,
        isLoggedIn: false,
        hasError: false
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
      }
    }
  }
</script>
