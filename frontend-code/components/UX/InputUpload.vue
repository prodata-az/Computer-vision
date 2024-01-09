<template>
  <input
    :id="id"
    type="file"
    :name="name"
    :accept="accept"
    :multiple="multiple"
    @change="change"
  >
</template>

<script setup>
  const props = defineProps({
    id: {type: String, default: 'id'},
    name: {type: String, default: 'name'},
    accept: {type: String, default: 'video/*'},
    multiple: {type: Boolean, default: false}
  })

  const emit = defineEmits(['change']);

  const change = (e) => {
    if (e.target.files.length) {
      emit('change', { file: e.target.files[0] });
    }
	}
</script>

<style lang="scss" scoped>
  @import '~/public/css/reset.css';
  @import '~/public/sass/palitra.scss';

  input[type='file'] {
    border: none;
  }
</style>
