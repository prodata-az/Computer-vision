<template>
  <div class="checklist">
    <div class="checklist__icon">
      <IconsChecked />
    </div>
  </div>
</template>

<script setup></script>

<style lang="scss" scoped>
@import '@/public/sass/variables.scss';

  .checklist {
    width: 100%;
    height: 100%;
    padding: 3px;
    border-radius: 6px;
    border: 1px solid $color-3;
    background: $color-19;
    display: flex;
    justify-content: center;
    align-items: center;
    &__icon {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100%;
    }
  }
</style>
