<template>
  <div class="field">
    <label class="label">{{ label }}</label>

    <div class="control has-icons-right">
      <input class="input" type="password" v-if="hidden"
        v-model="value"
        :class="{ 'is-danger': errors.length > 0 }"
        :name="name"
        @input="input" />

      <input class="input" type="text" autocomplete="false" v-else
        v-model="value"
        :class="{ 'is-danger': errors.length > 0 }"
        :name="name"
        @input="input" />

      <span class="icon is-small is-right is-clickable" @click="toggleDisplay()">
        <i v-if="hidden" class="fa fa-eye-slash"></i>
        <i v-else class="fa fa-eye"></i>
      </span>
    </div>

    <div class="strength">
      <span class="meter"
        :class="strength.className"
        :style="{ width: strength.width }">
      </span>
    </div>

    <p class="help is-danger" v-for="message in errors">{{ message }}</p>

    <p class="help" :class="strength.className">
      {{ strength.feedback.warning }}
    </p>
  </div>
</template>

<style lang="scss" scoped>
  .strength {
    background: #eee;
    height: 0.1em;

    margin-top: 0.5rem;

    border-radius: 3px;
    overflow: hidden;

    .meter {
      display: inherit;
      height: 0.1em;

      transition: width 300ms ease;
    }

    .meter.is-dark {
      background: #363636;
    }

    .meter.is-danger {
      background: #ff3860;
    }

    .meter.is-warning {
      background: #ffdd57;
    }

    .meter.is-primary {
      background: #00d1b2;
    }

    .meter.is-success {
      background: #23d160;
    }
  }
</style>

<script>
  import zxcvbn from 'zxcvbn'

  export default {
    props: {
      label: {
        type: String
      },

      name: {
        type: String,
        default: 'password'
      },

      dictionary: {
        type: Array,
        default () {
          return []
        }
      },

      errors: {
        type: Array,
        default () {
          return []
        }
      }
    },

    data () {
      return {
        hidden: true,
        value: ''
      }
    },

    computed: {
      strength () {
        const result = zxcvbn(this.value, this.dictionary)

        let className, feedback, computedWidth, width = 0

        switch (result.score) {
          case 0:
            className = 'is-dark'
            break

          case 1:
            className = 'is-danger'
            break

          case 2:
            className = 'is-warning'
            break

          case 3:
            className = 'is-primary'
            break

          case 4:
            className = 'is-success'
            break
        }

        computedWidth = result.guesses_log10 * 8

        if (computedWidth > 100) {
          computedWidth = 100
        }

        feedback = result.feedback
        width = `${computedWidth}%`

        return { className, feedback, width }
      }
    },

    methods: {
      input () {
        this.$emit('input', this.value)
      },

      toggleDisplay () {
        this.hidden = !this.hidden
      }
    }
  }
</script>
