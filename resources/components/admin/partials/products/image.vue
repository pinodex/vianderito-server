<template>
  <section>
    <figure class="image is-16by9">
      <img :src="previewObject">
    </figure>

    <b-upload v-model="selectedFiles" accept="image/*" @input="imageInput" native>
      <a class="button upload-button is-primary is-small is-fullwidth">
        <span class="icon is-small">
          <i class="fa fa-upload"></i>
        </span>

        <span>Click to upload (Max: 2MB)</span>
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

    methods: {
      imageInput (files) {
        if (files[0].size > 2000000) {
          this.$emit('fileSizeExceed')

          return
        }

        this.$emit('input', files)
      }
    },

    computed: {
      previewObject () {
        if (this.selectedFiles.length > 0 && this.selectedFiles[0].size <= 2000000) {
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
