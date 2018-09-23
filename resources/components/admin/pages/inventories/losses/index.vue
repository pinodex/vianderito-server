<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Inventory Losses</h1>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <button class="button is-primary is-rounded"
                    @click="modal.create = true"
                    v-if="$root.can('create_inventory')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Add Inventory Loss</span>
                  </button>
                </p>
              </div>
            </div>
          </div>
        </div>

        <losses :inventoryId="inventoryId"></losses>
      </div>

      <div class="column is-3" v-if="inventory.product">
        <div class="card">
          <div class="card-image">
            <figure class="image is-16by9">
              <img :src="inventory.product.picture.image">
            </figure>
          </div>

          <div class="card-content">
            <div class="media">
              <div class="media-content">
                <p class="title is-4">{{ inventory.product.name }}</p>
                <p class="subtitle is-6">{{ inventory.product.upc }}</p>
              </div>
            </div>

            <div class="content">
              <p class="is-size-6 is-marginless has-text-dark">
                <small><strong>Inventory ID</strong></small>
              </p>

              <p class="is-size-6 has-contents-below">
                {{ inventory.eid }}
              </p>

              <p class="is-size-6 is-marginless has-text-dark">
                <small><strong>Quantity</strong></small>
              </p>

              <p class="is-size-6 has-contents-below">
                {{ inventory.quantity }}
              </p>

              <p class="is-size-6 is-marginless has-text-dark">
                <small><strong>Stocks</strong></small>
              </p>

              <p class="is-size-6 has-contents-below">
                {{ inventory.stocks }}
              </p>

              <p class="is-size-6 is-marginless has-text-dark">
                <small><strong>Cost</strong></small>
              </p>

              <p class="is-size-6 has-contents-below">
                {{ inventory.cost | currency('₱') }}
              </p>

              <p class="is-size-6 is-marginless has-text-dark">
                <small><strong>Price</strong></small>
              </p>

              <p class="is-size-6 has-contents-below">
                {{ inventory.price | currency('₱') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal :active.sync="modal.create" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Add Inventory Loss</h1>

        <div class="modal-body">
          <create :id="inventoryId"></create>
        </div>
      </div>
    </b-modal>

    <b-modal :active.sync="modal.edit" :width="400">
      <div class="modal-box">
        <h1 class="modal-header">Edit Inventory Loss</h1>

        <div class="modal-body">
          <edit :id="inventoryId" :model="mountedModel"></edit>
        </div>
      </div>
    </b-modal>
  </section>
</template>

<script>
  import losses from '@admin/partials/inventories/losses/index'
  import create from '@admin/partials/inventories/losses/create'
  import edit from '@admin/partials/inventories/losses/edit'

  export default {
    inject: ['$inventory'],

    components: { losses, create, edit },

    data () {
      return {
        modal: {
          create: false,
          edit: false
        },

        inventory: {},
        inventoryId: null,

        mountedModel: null
      }
    },

    created () {
      this.inventoryId = this.$route.params.id
    },

    mounted () {
      this.$root.setPageTitle('Inventory Losses')

      this.$root.$on('inventory_losses:edit', model => {
        this.modal.edit = true

        this.mountedModel = model
      })

      this.$inventory.fetch(this.inventoryId)
        .then(response => this.inventory = response.data)
    },

    beforeDestroy () {
      this.$root.$off('inventory_losses:edit')
    }
  }
</script>
