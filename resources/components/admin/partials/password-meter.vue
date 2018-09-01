<template>
  <section v-if="result">
    <div class="strength">
      <span class="meter"
        :class="className"
        :style="meterStyle">
      </span>
    </div>

    <p class="help" :class="className">
      {{ result.feedback.warning }}
    </p>
  </section>
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
  import zxcvbnAsync from 'zxcvbn-async'
  
  const zxcvbn = zxcvbnAsync.load({ sync: true })

  const scoreClasses = [
    'is-dark', 'is-danger', 'is-warning', 'is-primary', 'is-success'
  ];

  export default {
    props: {
      dictionary: {
        type: Array,
        default: () => []
      },

      password: {
        type: String,
        default: () => ''
      }
    },

    data () {
      return {
        result: null
      }
    },

    computed: {
      className () {
        if (this.result.score >= scoreClasses.length) {
          return scoreClasses[0]
        }

        return scoreClasses[this.result.score]
      },

      width () {
        let width = this.result.guesses_log10 * 8

        if (width > 100) {
          width = 100
        }

        return width
      },

      meterStyle () {
        return {
          width: `${this.width}%`
        }
      }
    },

    mounted () {
      this.result = zxcvbn(this.password, this.dictionary)
    },

    watch: {
      password () {
        this.result = zxcvbn(this.password, this.dictionary)
      }
    }
  }
</script>
