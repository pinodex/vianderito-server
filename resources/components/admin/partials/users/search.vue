<template>
  <form @submit.prevent="search()">
    <div class="field">
      <label class="label">Name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.name" autocomplete="name" />
      </div>
    </div>

    <div class="field">
      <label class="label">Username</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.username" autocomplete="username" />
      </div>
    </div>

    <div class="field">
      <label class="label">Email Address</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.email_address" autocomplete="email" />
      </div>
    </div>

    <div class="field">
      <label class="label">Phone Number</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.phone_number" autocomplete="phone-local" />
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
    props: {
      query: {
        type: Object,
        default: {}
      }
    },
    
    methods: {
      clear () {
        this.$root.$emit('users:query:clear')
      },

      search () {
        this.$root.$emit('users:query', this.query)
      }
    }
  }  
</script>
