<template>
  <div class="container">
    <div class="popup">
      <div class="popup__content">
        <div class="popup__header"></div>
        <div class="popup_main"></div>
        <div class="popup__footer"></div>
      </div>
    </div>
  </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>
@import '@/public/sass/variables.scss';
  .container {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: transparent;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .popup {
    width: auto;
    height: auto;
    display: flex;
    background-color: $color-2;
    &__header {
      width: 100%;
      height: 100%;
      padding: 20px 24px;
      display: flex;
      justify-content: left;
    }
    &__main {
      width: 100%;
      height: 100%;
      padding: 10px 20px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    &__footer {
      width: 100%;
      height: 100%;
      padding: 16px 20px;
      display: flex;
      justify-content: right;
      align-items: center;
    }
  }
</style>