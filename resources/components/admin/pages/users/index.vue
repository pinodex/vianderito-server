<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Users</h1>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item" v-if="$root.can('browse_users')">
              <div class="field has-addons">
                <namesearch :query="query" module="users"></namesearch>

                <p class="control">
                  <button class="button is-rounded" type="button" title="More..." 
                    :class="{ 'is-warning': isSearchActive }"
                    @click="searchVisible = true">
                    <span class="icon">
                      <i class="fa fa-ellipsis-h"></i>
                    </span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <users :query="query"></users>
      </div>
    </div>

    <b-modal :active.sync="searchVisible" :width="360">
      <div class="modal-box">
        <h1 class="modal-header">Search User</h1>

        <div class="modal-body">
          <search :query="query"></search>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="640">
      <div class="modal-box">
        <h1 class="modal-header">Edit Account</h1>

        <div class="modal-body">
          <edit :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import users from '@admin/partials/users/index'
  import search from '@admin/partials/users/search'
  import edit from '@admin/partials/users/edit'

  import namesearch from '@admin/partials/namesearch'

  export default {
    components: { users, search, edit, namesearch },

    data () {
      return {
        modal: {
          edit: false
        },

        mountedModel: null,
        query: {},

        searchVisible: false,
        isSearchActive: false
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.$on('users:query:clear', () => {
        this.isSearchActive = false
        this.searchVisible = false
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('users:query'), 1)
      })

      this.$root.$on('users:query', data => {
        this.isSearchActive = false
        
        for (var key in this.query) {
          if (this.query.hasOwnProperty(key)) {
            if (key == 'with') continue

            if (this.query[key].length > 0) {
              this.isSearchActive = true
            }
          }
        }

        this.searchVisible = false
      })

      this.$root.$on('users:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    },

    beforeDestroy () {
      this.$root.$off('users:query:clear')

      this.$root.$off('users:query')
      
      this.$root.$off('users:edit')
    }
  }
</script>
