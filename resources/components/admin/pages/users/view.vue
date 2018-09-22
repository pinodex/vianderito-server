<template>
  <section v-if="user">
    <div class="hero is-info is-bold">
      <div class="hero-body">
        <div class="ui-container">
          <article class="media is-vcentered">
            <figure class="media-left">
              <p class="image is-64x64 avatar">
                <img :src="user.picture.thumbnail" />
              </p>
            </figure>

            <div class="media-content">
              <div class="content">
                <p class="subtitle is-5 is-marginless">
                  <strong>{{ user.name }}</strong>
                </p>

                <p class="is-size-6">@{{ user.username }}</p>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>

    <div class="main-container ui-container">
      <div class="columns">
        <div class="column is-3">
          <div class="box">
            <p class="is-size-6 is-marginless has-text-dark">
              <small><strong>Created at</strong></small>
            </p>

            <p class="is-size-6 has-contents-below">
              {{ user.created_at | moment('MMM DD, YYYY hh:mm A') }}
            </p>

            <p class="is-size-6 is-marginless has-text-dark">
              <small><strong>Last Login</strong></small>
            </p>

            <p class="is-size-6 has-contents-below">
              {{ user.last_login_at | moment('MMM DD, YYYY hh:mm A') }}
            </p>
          </div>
        </div>

        <div class="column">
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  export default {
    inject: ['$user'],

    data () {
      return {
        user: null
      }
    },

    mounted () {
      const loadingComponent = this.$loading.open()

      let id = this.$route.params.id

      this.$user.fetch(id)
        .then(response => {
          loadingComponent.close()

          this.user = response.data

          this.$root.setPageTitle(this.user.name)
        })
    }
  }
</script>
