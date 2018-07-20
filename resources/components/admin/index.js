import Vue from 'vue'

let context = require.context('./', true, /\.(vue)$/)

context.keys().forEach(name => {
  let id = name
    .replace('./', '') // Remove leading directory name
    .replace(/\//g, '-') // Replace slashes with dash
    .replace('.vue', '') // Remove .vue file ext

  Vue.component(id, context(name))
});

export default {}
