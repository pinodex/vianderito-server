<template>
  <div class="field-department has-contents-below">
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
      <label class="label">Description (optional)</label>

      <div class="control">
        <textarea class="textarea" name="description"
          :class="{ 'is-danger': errors.description }"
          v-model="model.description"></textarea>
      </div>

      <p class="help is-danger" v-for="message in errors.description">{{ message }}</p>
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
