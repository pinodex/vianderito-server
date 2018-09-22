<template>
  <div class="media is-vcentered" v-if="model.deleted_at">
    <figure class="media-left">
      <p class="image is-48x48 avatar">
        <img :src="model.picture.thumbnail" />
      </p>
    </figure>

    <div class="media-content">
      <div class="content">
        <p class="is-marginless">
          <strong>{{ model.name }}</strong><br />
          <small>@{{ model.username}}</small>
        </p>
      </div>
    </div>
  </div>

  <router-link v-else class="media is-vcentered" :to="{ name: 'users.view', params: { id: model.id }}">
    <figure class="media-left">
      <p class="image is-48x48 avatar">
        <img :src="model.picture.thumbnail" />
      </p>
    </figure>

    <div class="media-content">
      <div class="content">
        <p class="is-marginless">
          <strong>{{ model.name }}</strong><br />
          <small>@{{ model.username}}</small>
        </p>
      </div>
    </div>
  </router-link>
</template>

<script>
  export default {
    props: {
      model: {
        type: Object
      }
    }
  }
</script>
