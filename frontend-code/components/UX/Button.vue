<script setup>
  const props = defineProps({
    type: { type: String, default: 'button' },
    name: { type: String, default: 'name' },
    disabled: { type: Boolean, default: false },
  });
</script>

<template>
  <button
    :type="type"
    :name="name"
    class="btn"
    :disabled="disabled"
  >
    <slot />
  </button>
</template>

<style lang="scss" scoped>
  @import '~/public/sass/variables.scss';

  .btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    column-gap: 8px;
    width: fit-content;
    position: relative;
    background-color: $btn-bg;
    border-radius: 8px;
    padding: 10px 18px;
    outline: none;
    border: 1px solid $btn-border-color;
    transition: all 0.15s ease-in-out;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    color: $color-32;
    font-family: Inter;
    font-size: 1rem;
    font-weight: 600;
    &:hover {
      background-color: $btn-bg-hover;
    }
    &:active {
      background-color: $btn-bg-active;
    }
  }
</style>
