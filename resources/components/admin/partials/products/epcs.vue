<template>
  <section>
    <form @submit.prevent="submitForm" class="has-contents-below">
      <div class="field has-addons">
        <div class="control is-expanded">
          <input class="input" type="text" placeholder="Enter or scan EPC Code" required
            v-model="model.code" />
        </div>

        <div class="control">
          <button type="submit" class="button is-primary">
            <span class="icon">
              <i class="fa fa-plus"></i>
            </span>

            <span>Add</span>
          </button>
        </div>
      </div>

      <p class="help is-danger" v-if="duplicateError">
        EPC Code is already in the list
      </p>
    </form>

    <p class="has-contents-below">EPC Table:</p>

    <div class="epc-table has-contents-below" v-if="epcs.length > 0">
      <table class="table is-striped is-fullwidth">
        <tbody>
          <tr v-for="(epc, i) in epcs">
            <td>{{ epc.code }}</td>
            <td class="is-fit">
              <button class="button is-small is-danger" @click="removeCode(i)">
                <span class="icon">
                  <i class="fa fa-trash"></i>
                </span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else>
      <p class="has-text-grey has-contents-below">No EPC codes added for this product</p>
    </div>

    <div class="field">
      <div class="control">
        <button type="button" class="button is-primary is-outlined is-fullwidth"
          @click="close">Close</button>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
  .epc-table {
    max-height: 200px;
    overflow-y: auto;
  }
</style>

<script>
  export default {
    props: {
      epcs: {
        type: Array
      }
    },

    data () {
      return {
        model: {},

        duplicateError: false,

        kiosk: null
      }
    },

    mounted () {
      this.kiosk = this.$echo.channel('general')

      this.kiosk.listen('.tag.receive', items => items.forEach(code => {
        if (this.epcs.findIndex(epc => epc.code == code) == -1)
          this.epcs.push({ code })
      }))
    },

    methods: {
      submitForm () {
        this.duplicateError = false

        if (this.epcs.findIndex(e => e.code == this.model.code) >= 0) {
          this.duplicateError = true
          return
        }
        
        this.epcs.push({ code: this.model.code })

        this.model = {}
      },

      removeCode (index) {
        this.epcs.splice(index, 1);
      },

      close () {
        this.$parent.close()
      }
    }
  }
</script>
