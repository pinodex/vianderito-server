<template>
  <section>
    <b-tabs>
      <b-tab-item v-for="group in groups" :key="group" :label="group.toUpperCase()">
        <b-table checkable
          :data="permissions[group]"
          :checked-rows.sync="model.permissions"
          :custom-is-checked="isChecked"
          :loading="isLoading">

          <template slot-scope="props">
            <b-table-column label="Name">
              {{ props.row.name }}
            </b-table-column>

            <b-table-column label="Description">
              {{ props.row.description }}
            </b-table-column>
          </template>
        </b-table>
      </b-tab-item>
    </b-tabs>

    <div class="field is-pulled-right">
      <div class="control">
        <button class="button is-success"
          @click="submitRequest"
          :class="{ 'is-loading': isLoading }"
          :disabled="isLoading">Save Changes</button>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
  .b-table /deep/ .table-wrapper {
    min-height: 200px;
    margin-bottom: 0;

    & + .level {
      display: none;
    }
  }

  .field {
    padding: 1rem;
  }
</style>

<script>
  export default {
    inject: ['$permission', '$group'],
    props: ['model'],

    data () {
      return {
        groups: [],
        permissions: [],
        isLoading: false
      }
    },

    mounted () {
      this.isLoading = true

      this.$permission.all()
        .then(response => {
          let data = response.data

          console.log(response.data)

          this.permissions = data.groupBy('category')

          this.groups = [...new Set(data.map(obj => obj.category))]

          this.isLoading = false
        })
    },

    methods: {
      isChecked (a, b) {
        return a.id == b.id
      },

      submitRequest () {
        this.isLoading = true

        let ids = this.model.permissions.map(item => item.id)

        this.$group.setPermissions(this.model.id, ids)
          .then(response => {
            this.$toast.open({
              message: 'Changes to group permissions has been saved',
              type: 'is-success'
            })
            
            this.close()
          })
          .finally(() => this.isLoading = false)
      },

      close () {
        this.$parent.close()
      }
    }
  }
</script>