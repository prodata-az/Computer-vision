<template>
  <div class="container">
    <div class="container__main">
      <slot name="main"></slot>
    </div>
    <div
      v-if="footer"
      class="container__footer"
    >
      <div class="buttons">
        <div class="buttons__container">
          <slot name="second-btn"></slot>
          <slot name="third-btn"></slot>
          <slot name="fourth-btn"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  const props = defineProps({
    footer: { type: Boolean, default: true }
  });
</script>

<style lang="scss" scoped>
  @import '/public/sass/variables';

  .container {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid $section-border;
    box-shadow: 0px 6px 15px 0px rgba(0, 0, 0, 0.02);
    background-color: $section-bg;
    &__header {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    &__title {
      flex: 1 1 fit-content;
      font-size: 1.25rem;
      font-weight: 600;
    }
    .buttons {
      background-color: #ffffff;
      &__icon {
        width: 20px;
        height: 20px;
      }
      &__text {
        font-weight: 600;
        line-height: 1.25rem;
      }
      &__container {
        display: flex;
        justify-content: right;
        column-gap: 8px;
      }
    }
  }
</style>
