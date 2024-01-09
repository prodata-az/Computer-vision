<template>
  <div class="container">
    <slot></slot>
  </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>
@import '/public/sass/variables';

  .container {
    width: 100%;
    height: 100%;
    background-color: $color-2;
    border-radius: 8px;
    border: 1px solid $color-36;
    box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.05);
    display: flex;
    padding: 10px;
    flex-direction: column;
    gap: 10px;
  }
</style>