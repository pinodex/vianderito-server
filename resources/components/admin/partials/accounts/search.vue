<template>
  <form @submit.prevent="search()">
    <div class="field">
      <label class="label">First name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.first_name" autocomplete="given-name" />
      </div>
    </div>

    <div class="field">
      <label class="label">Middle name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.middle_name" autocomplete="additional-name" />
      </div>
    </div>

    <div class="field">
      <label class="label">Last name</label>
      
      <div class="control">
        <input class="input" type="text" v-model="query.last_name" autocomplete="family-name" />
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
        <input class="input" type="text" v-model="query.email" autocomplete="email" />
      </div>
    </div>

    <div class="field">
      <label class="label">Department</label>
      
      <div class="control">
        <div class="select is-fullwidth">
          <select name="department_id" v-model="query.department_id" :disabled="departmentsLoading">
            <option v-for="department in departments" :value="department.id">
              {{ department.name }}
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
    inject: ['$department'],

    props: {
      query: {
        type: Object,
        default: {}
      }
    },

    data () {
      return {
        departments: [],
        departmentsLoading: true
      }
    },

    mounted () {
      this.$department.all()
        .then(response => this.departments = response.data)
        .finally(() => this.departmentsLoading = false)
    },

    methods: {
      clear () {
        this.$root.$emit('accounts:query:clear')
      },

      search () {
        this.$root.$emit('accounts:query', this.query)
      }
    }
  }  
</script>
