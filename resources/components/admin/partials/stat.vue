<template>
  <div class="box" :style="boxStyle">
    <p class="stat-title">{{ title }}</p>
    
    <p class="stat-value">
      <template v-if="value != null">{{ value | currency('', 0) }}</template>
      <template v-else>...</template>
    </p>
  </div>
</template>

<style lang="scss" scoped>
  .box {
    background-color: #fff;
    background-repeat: no-repeat;
    background-position: center right 1rem;
    background-size: 64px 64px;
  }

  .stat-title {
    font-size: 1rem;
    color: #999;
  }

  .stat-value {
    font-size: 2rem;
  }
</style>

<script>
  export default {
    props: {
      title: {
        type: String
      },

      value: {
        type: Number
      },

      background: {
        type: String,
        default: () => null
      }
    },

    computed: {
      boxStyle () {
        if (this.background)
          return { 'background-image': `url(${this.background})` }
      }
    }
  }
</script>
