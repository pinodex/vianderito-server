<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Product Inventory Losses</h1>
            </div>
          </div>
        </div>

        <losses :productId="productId"></losses>
      </div>
    </div>
  </section>
</template>

<script>
  import losses from '@admin/partials/products/losses/index'

  export default {
    inject: ['$product'],

    components: { losses },

    data () {
      return {
        product: null,
        productId: null,
      }
    },

    created () {
      this.productId = this.$route.params.id
    },

    mounted () {
      this.$root.setPageTitle('Product Inventory Losses')

      this.$product.fetch(this.productId)
        .then(response => this.product = response.data)
    }
  }
</script>
