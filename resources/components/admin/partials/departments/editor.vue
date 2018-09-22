<template>
  <div class="field">
    <label class="label">Name</label>

    <div class="control">
      <input class="input" type="text" name="name" autocomplete="name" required
        :class="{ 'is-danger': errors.name }"
        v-model="model.name" />
    </div>

    <p class="help is-danger" v-for="message in errors.name">{{ message }}</p>
  </div>
</template>

<style lang="scss" scoped>
  .field-label {
    flex-grow: 2;
  }
</style>

<script>
  export default {
    props: ['model', 'errors']
  }
</script>
