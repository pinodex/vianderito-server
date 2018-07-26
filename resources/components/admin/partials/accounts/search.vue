<template>
  <form @submit.prevent="search()">
    <div class="field">
      <label class="label">First name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.first_name" />
      </div>
    </div>

    <div class="field">
      <label class="label">Middle name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.middle_name" />
      </div>
    </div>

    <div class="field">
      <label class="label">Last name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.last_name" />
      </div>
    </div>

    <div class="field">
      <label class="label">Username</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.username" />
      </div>
    </div>

    <div class="field">
      <label class="label">Group</label>
      
      <div class="control">
        <div class="select is-fullwidth">
          <select name="group_id" v-model="query.group_id" :disabled="groupsLoading">
            <option v-for="group in groups" :value="group.id">
              {{ group.name }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="field is-grouped is-pulled-right">
      <div class="control">
        <button type="button" class="button" @click="clear()">Clear</button>
      </div>

      <div class="control">
        <button type="submit" class="button is-primary">
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
    inject: ['$group'],

    data () {
      return {
        query: {},
        groups: [],
        groupsLoading: true
      }
    },

    mounted () {
      this.$group.all()
        .then(response => this.groups = response.data)
        .finally(() => this.groupsLoading = false)
    },

    methods: {
      clear () {
        this.query = {}

        this.search()
      },

      search () {
        this.$root.$emit('model:query', this.query)
      }
    }
  }  
</script>
