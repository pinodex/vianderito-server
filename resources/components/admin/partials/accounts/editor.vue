<template>
  <div class="columns">
    <div class="column">
      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label class="label">First Name</label>
        </div>

        <div class="field-body">
          <div class="field">
            <p class="control">
              <input class="input" type="text" name="first_name" autocomplete="given-name" required
                :class="{ 'is-danger': errors.first_name }"
                v-model="model.first_name" />
            </p>

            <p class="help is-danger" v-for="message in errors.first_name">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Middle Name</label>
        </div>

        <div class="field-body">
          <div class="field">
            <p class="control">
              <input class="input" type="text" name="middle_name" autocomplete="additional-name" 
                :class="{ 'is-danger': errors.middle_name }"
                v-model="model.middle_name" />
            </p>

            <p class="help is-danger" v-for="message in errors.middle_name">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Last Name</label>
        </div>

        <div class="field-body">
          <div class="field">
            <p class="control">
              <input class="input" type="text" name="last_name" autocomplete="family-name" required
                :class="{ 'is-danger': errors.last_name }"
                v-model="model.last_name" />
            </p>

            <p class="help is-danger" v-for="message in errors.last_name">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Username</label>
        </div>

        <div class="field-body">
          <div class="field">
            <p class="control">
              <input class="input" type="text" name="username" autocomplete="username" required
                :class="{ 'is-danger': errors.username }"
                v-model="model.username" />
            </p>

            <p class="help is-danger" v-for="message in errors.username">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Group</label>
        </div>

        <div class="field-body">
          <div class="field">
            <b-select name="group_id" v-model="model.group_id" expanded
              :class="{ 'is-danger': errors.group_id }"
              :loading="groupsLoading">

              <option v-for="group in groups" :value="group.id">
                {{ group.name }}
              </option>
            </b-select>

            <p class="help is-danger" v-for="message in errors.group">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">&nbsp;</div>

        <div class="field-body">
          <div class="field">
            <p class="has-text-grey is-size-7">
              <em>Password will be automatically generated.</em>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="column is-3">
      <figure class="image avatar is-square">
        <img :src="avatar">
      </figure>

      <b-upload accept="image/*" @input="avatarSelect">
        <a class="button is-small is-fullwidth">
          <span class="icon is-small">
            <i class="fa fa-upload"></i>
          </span>

          <span>Click to upload</span>
        </a>
      </b-upload>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .avatar {
    margin-bottom: 1rem;
  }

  .field-label {
    flex-grow: 2;
  }
</style>

<script>
  export default {
    inject: ['$group'],

    props: ['model', 'errors'],

    data () {
      return {
        groups: [],
        groupsLoading: true,

        avatar: '/assets/img/default-avatar.png'
      }
    },

    mounted () {
      if ('picture' in this.model) {
        this.avatar = this.model.picture.image
      }

      this.$group.all()
        .then(response => this.groups = response.data)
        .finally(() => this.groupsLoading = false)
    },

    methods: {
      avatarSelect (value) {
        let reader = new FileReader(),
            vm = this

        reader.onload = e => {
          vm.avatar = e.target.result
        }

        reader.readAsDataURL(value[0])

        this.$emit('filesChange', value)
      }
    }
  }
</script>
