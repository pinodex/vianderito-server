<template>
  <section class="main-container">
    <div class="columns">
      <div class="column">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Coupons</h1>
            </div>
          </div>

          <div class="level-right">
            <div class="level-item">
              <div class="field is-grouped">
                <p class="control">
                  <router-link class="button is-primary is-rounded"
                    :to="{ name: 'coupons.create' }"
                    v-if="$root.can('create_coupon')">
                    <span class="icon">
                      <i class="fa fa-plus"></i>
                    </span>
                    
                    <span>Create Coupon</span>
                  </router-link>
                </p>
              </div>
            </div>
          </div>
        </div>

        <coupons :query="query"></coupons>
      </div>
    </div>
  </section>
</template>

<script>
  import coupons from '@admin/partials/coupons/index'

  export default {
    components: { coupons },

    data () {
      return {
        query: {}
      }
    },

    created () {
      this.query = Object.assign({}, this.query, this.$route.query)
    },

    mounted () {
      this.$root.$on('coupons:query:clear', () => {
        this.query = {}

        setTimeout(() => 
          this.$root.$emit('coupons:query'), 1)
      })
    },

    beforeDestroy () {
      this.$root.$off('coupons:query:clear')
    }
  }
</script>
