<template>
  <section>
    <figure class="image is-16by9">
      <img :src="previewObject">
    </figure>

    <b-upload v-model="selectedFiles" accept="image/*" native
      @input="$emit('input', $event)">
      <a class="button upload-button is-primary is-small is-fullwidth">
        <span class="icon is-small">
          <i class="fa fa-upload"></i>
        </span>

        <span>Click to upload</span>
      </a>
    </b-upload>
  </section>
</template>

<style lang="scss" scoped>
  .image {
    background: #fff;
  }

  .upload-button {
    border-radius: 0;
  }
</style>

<script>
  export default {
    props: {
      preview: {
        type: String,
        default: null
      }
    },

    data () {
      return {
        selectedFiles: []
      }
    },

    computed: {
      previewObject () {
        if (this.selectedFiles.length > 0) {
          return URL.createObjectURL(this.selectedFiles[0])
        }

        if (this.preview) {
          return this.preview
        }

        return '/assets/img/generic-product-image.png'
      }
    }
  }
</script>
