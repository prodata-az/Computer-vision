<template>
  <video
    :id="id"
    :autoplay="autoplay"
    :poster="poster"
    :controls="controls"
    :loop="loop"
    :src="src"
  >
  </video>
</template>

<script setup>
  const props = defineProps({
    id: {type: String, default: ''},
    autoplay: {type: Boolean, default: false},
    poster: {type: String, default: ''},
    controls: {type: Boolean, default: true},
    src: {type: String, default: ''},
    type: {type: String, default: ''},
    loop: {type: Boolean, default: false},
    text: {type: String, default: 'There isn`t a video'}
  })
</script>

<style lang="scss" scoped>
  video {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
</style>
