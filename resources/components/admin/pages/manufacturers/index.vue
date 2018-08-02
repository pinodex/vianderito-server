<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Manufacturers</h1>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-primary is-rounded"
                    @click="modal.create = true"
                    v-if="$root.can('create_manufacturer')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Add Manufacturer</span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <manufacturers :query="query"></manufacturers>
      </div>

      <div class="column is-3" v-if="$root.can('browse_manufacturers')">
         <div class="panel">
          <div class="panel-heading">
            <span class="icon is-small">
              <i class="fa fa-search"></i>
            </span>

            <span>Search</span>
          </div>

          <div class="panel-block">
            <search :query="query"></search>
          </div>
        </div>
      </div>
    </div>

    <b-modal :active.sync="modal.create" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Add Manufacturer</h1>

        <div class="modal-body">
          <create></create>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Edit Manufacturer</h1>

        <div class="modal-body">
          <edit :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import manufacturers from '@admin/partials/manufacturers/index'
  import search from '@admin/partials/manufacturers/search'
  import create from '@admin/partials/manufacturers/create'
  import edit from '@admin/partials/manufacturers/edit'

  export default {
    components: { manufacturers, search, create, edit },

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
      this.$root.$on('manufacturers:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('manufacturers:query'), 1)
      })

      this.$root.$on('manufacturers:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })
    },

    beforeDestroy () {

    }
  }
</script>
