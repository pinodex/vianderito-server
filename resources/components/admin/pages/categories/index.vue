<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <b-tabs @change="switchView">
                <b-tab-item label="Categories"></b-tab-item>
                <b-tab-item label="Archive"></b-tab-item>
              </b-tabs>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item" v-if="$root.can('browse_categories')">
              <div class="field">
                <namesearch :query="query" module="categories"></namesearch>
              </div>
            </div>

            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-primary is-rounded"
                    @click="modal.create = true"
                    v-if="$root.can('create_category')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Create Category</span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <categories :query="query"></categories>
      </div>
    </div>

    <b-modal :active.sync="modal.create" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Create Category</h1>

        <div class="modal-body">
          <create></create>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Edit Category</h1>

        <div class="modal-body">
          <edit :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import categories from '@admin/partials/categories/index'
  import create from '@admin/partials/categories/create'
  import edit from '@admin/partials/categories/edit'

  import namesearch from '@admin/partials/namesearch'

  export default {
    components: { categories, create, edit, namesearch },

    data () {
      return {
        modal: {
          create: false,
          edit: false
        },

        mountedModel: null,
        query: {}
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.setPageTitle('Categories')

      this.$root.$on('categories:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('categories:query'), 1)
      })

      this.$root.$on('categories:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    },

    beforeDestroy () {
      this.$root.$off('categories:query:clear')

      this.$root.$off('categories:edit')
    },

    methods: {
      switchView (view) {
        this.query.trashed = view

        this.$root.$emit('categories:query')
      }
    }
  }
</script>
