<template>
  <section class="main-container">
    <tabs></tabs>
  </section>
</template>

<script>
  import tabs from '@admin/partials/reports/tabs'

  export default {
    components: { tabs },

    inject: ['$report'],

    data () {
      return {
        
      }
    },

    mounted () {
    }
  }
</script>
