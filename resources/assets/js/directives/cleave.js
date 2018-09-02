import Cleave from 'cleave.js'

const cleave = {
  name: 'cleave',

  bind(input, binding) {
    input._vCleave = new Cleave(input, binding.value)
  },

  update(input, binding) {
    input._vCleave.destroy()
    input._vCleave = new Cleave(input, binding.value)
  },

  unbind(input) {
    input._vCleave.destroy()
  }
}

export default cleave
