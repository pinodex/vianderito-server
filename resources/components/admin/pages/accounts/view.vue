<template>
  <section v-if="account">
    <div class="hero is-primary is-bold">
      <div class="hero-body">
        <div class="ui-container">
          <article class="media is-vcentered">
            <figure class="media-left">
              <p class="image is-64x64">
                <img :src="account.picture.thumb" />
              </p>
            </figure>

            <div class="media-content">
              <div class="content">
                <p class="subtitle is-5 is-marginless">
                  <strong>{{ account.name }}</strong>
                </p>

                <p class="is-size-6">@{{ account.username }}</p>
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
              <small><strong>Created</strong></small>
            </p>

            <p class="is-size-6 has-contents-below">
              {{ account.created_at | moment('MMM DD, YYYY hh:mm A') }}
            </p>

            <p class="is-size-6 is-marginless has-text-dark">
              <small><strong>Last Login</strong></small>
            </p>

            <p class="is-size-6 has-contents-below">
              {{ account.last_login_at | moment('MMM DD, YYYY hh:mm A') }}
            </p>

            <template v-if="account.group">
              <hr />

              <p class="is-size-6 is-marginless has-text-dark">
                <small><strong>Group</strong></small>
              </p>

              <p class="is-size-6 has-contents-below">
                {{ account.group.name }}
              </p>

              <p class="is-size-6 is-marginless has-text-dark">
                <small><strong>Permissions</strong></small>
              </p>

              <ul class="is-size-6">
                <li v-for="permission in account.group.permissions" :key="permission.id">
                  {{ permission.name }}
                </li>
              </ul>
            </template>
          </div>
        </div>

        <div class="column">
          <h2 class="is-size-4 has-contents-below">Account Logs</h2>

          <logs :id="account.id"></logs>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import logs from '@admin/partials/accounts/logs'

  export default {
    inject: ['$account'],

    components: { logs },

    data () {
      return {
        account: null
      }
    },

    mounted () {
      const loadingComponent = this.$loading.open()

      let id = this.$route.params.id

      this.$account.fetch(id)
        .then(response => this.account = response.data)
        .then(() => loadingComponent.close())
    }
  }
</script>
