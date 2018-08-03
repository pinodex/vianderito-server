<template>
  <form @submit.prevent="search()">
    <div class="field">
      <label class="label">Name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.name" />
      </div>
    </div>

    <div class="field is-grouped is-pulled-right">
      <div class="control">
        <button type="button" class="button" @click="clear()">Clear</button>
      </div>

      <div class="control">
        <button type="submit" class="button is-info">
          <span class="icon is-small">
            <i class="fa fa-search"></i>
          </span>

          <span>Search</span>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  export default {
    inject: ['$category'],

    props: {
      query: {
        type: Object,
        default: {}
      }
    },

    methods: {
      clear () {
        this.$root.$emit('categories:query:clear')
      },

      search () {
        this.$root.$emit('categories:query', this.query)
      }
    }
  }  
</script>
