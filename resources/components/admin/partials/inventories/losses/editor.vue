<template>
  <div class="field-department has-contents-below">
    <div class="field">
      <label class="label">Units</label>

      <div class="control">
        <input class="input" type="number" name="units" required
          :class="{ 'is-danger': errors.units }"
          v-model.number="model.units" />
      </div>

      <p class="help is-danger" v-for="message in errors.units">{{ message }}</p>
    </div>

    <div class="field">
      <label class="label">Remarks</label>

      <div class="control">
        <textarea class="textarea" name="remarks"
          :class="{ 'is-danger': errors.remarks }"
          v-model="model.remarks"></textarea>
      </div>

      <p class="help is-danger" v-for="message in errors.remarks">{{ message }}</p>
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
