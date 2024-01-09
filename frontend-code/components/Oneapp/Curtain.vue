<template>
  <div
    class="curtain"
    @click.self="close"
  >
    <slot />
  </div>
</template>

<script setup>
  const emit = defineEmits(['close']);

  const close = () => {
    emit('close');
  }
</script>

<style scoped>
  .curtain{
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #00000040;
    z-index: 10000;
    width: 100%;
    height: 100%;
  }
</style>
