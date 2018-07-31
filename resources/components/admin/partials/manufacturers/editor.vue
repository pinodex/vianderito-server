<template>
  <div class="field-group has-contents-below">
    <div class="field">
      <label class="label">Name</label>

      <div class="control">
        <input class="input" type="text" name="name" autocomplete="name" required
          :class="{ 'is-danger': errors.name }"
          v-model="model.name" />
      </div>

      <p class="help is-danger" v-for="message in errors.name">{{ message }}</p>
    </div>

    <div class="field">
      <label class="label">Code</label>

      <div class="control">
        <input class="input" type="text" name="code" required
          :class="{ 'is-danger': errors.code }"
          v-model="model.code" />
      </div>

      <p class="help is-danger" v-for="message in errors.code">{{ message }}</p>
    </div>
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
