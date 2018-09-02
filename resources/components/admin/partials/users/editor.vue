<template>
  <div class="columns">
    <div class="column">
      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label class="label">Name</label>
        </div>

        <div class="field-body">
          <div class="field">
            <p class="control">
              <input class="input" type="text" name="name" autocomplete="name" required
                :class="{ 'is-danger': errors.name }"
                v-model="model.name" />
            </p>

            <p class="help is-danger" v-for="message in errors.name">{{ message }}</p>
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
              <input class="input" type="text" name="username" autocomplete="username" 
                :class="{ 'is-danger': errors.username }"
                v-model="model.username" />
            </p>

            <p class="help is-danger" v-for="message in errors.username">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Email Address</label>
        </div>

        <div class="field-body">
          <div class="field">
            <p class="control">
              <input class="input" type="text" name="email_address" autocomplete="email"
                :class="{ 'is-danger': errors.email_address }"
                v-model="model.email_address" />
            </p>

            <p class="help is-danger" v-for="message in errors.email_address">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Phone Number</label>
        </div>

        <div class="field-body">
          <div class="field">
            <p class="control">
              <input class="input" type="text" name="phone_number" autocomplete="phone-local"
                :class="{ 'is-danger': errors.phone_number }"
                v-cleave="masks.phone"
                v-model="maskedPhoneNumber"
                @input="e => model.phone_number = e.target._vCleave.getRawValue()" />
            </p>

            <p class="help is-danger" v-for="message in errors.phone_number">{{ message }}</p>
          </div>
        </div>
      </div>

       <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Verification Stsatus</label>
        </div>

        <div class="field-body">
          <div class="field">
            <div class="select is-fullwidth">
              <select v-model.boolean="model.is_verified">
                <option :value="false">Unverified</option>
                <option :value="true">Verified</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!--
      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Password</label>
        </div>

        <div class="field-body">
          <div class="field">
            <a href="#" class="has-text-danger"
              @click.prevent="requestPasswordReset()">Reset Password</a>
          </div>
        </div>
      </div>
      -->
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

      <p class="is-size-7 has-text-centered">Max file size: 2MB</p>
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
  import cleave from '@directives/cleave'
  import phone from '@masks/phone'

  export default {
    inject: ['$group'],

    props: ['model', 'errors'],

    directives: { cleave },

    data () {
      return {
        groups: [],
        groupsLoading: true,

        masks: { phone },
        maskedPhoneNumber: '',

        avatar: '/assets/img/default-avatar.png'
      }
    },

    mounted () {
      if (this.model.picture) {
        this.avatar = this.model.picture.image
      }

      if (this.model.phone_number) {
        this.maskedPhoneNumber = this.model.phone_number
      }

      this.$group.all()
        .then(response => this.groups = response.data)
        .finally(() => this.groupsLoading = false)
    },

    methods: {
      avatarSelect (value) {
        if (value.length == 0) {
          return
        }

        if (value[0].size > 2000000) {
          this.$emit('fileSizeExceed')

          return
        }

        let reader = new FileReader(),
            vm = this

        reader.onload = e => {
          vm.avatar = e.target.result
        }

        reader.readAsDataURL(value[0])

        this.$emit('filesChange', value)
      },

      requestPasswordReset () {
        this.$emit('passwordResetRequest')
      }
    }
  }
</script>
