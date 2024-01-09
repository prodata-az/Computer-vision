<template>
  <div class="container">
    <div class="container__header">
      <div class="container__title">
        <span>
          {{ title }}
        </span>
      </div>
      <div
        class="container__btns"
        @click="isClosed = !isClosed"
      >
        <div class="btn">
          <IconsArrowDown :class="{ turn: !isClosed }" />
        </div>
      </div>
    </div>

    <div
      class="container__main"
      :class="{
        hidden: isClosed,
      }"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
  const props = defineProps({
    title: { type: String, default: '' },
  });

  let isClosed = ref(false);
</script>

<style lang="scss" scoped>
  @import '/public/sass/variables';

  .container {
    width: 100%;
    max-height: auto;
    display: flex;
    flex-direction: column;
    row-gap: 16px;
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
      color: $color-17;
      font-family: Inter;
      font-size: 1.25rem;
      font-weight: 600;
    }
  }
  .btn {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 44px;
    height: 44px;
    border: 1px solid #eaecf0;
    border-radius: 8px;
    .turn {
      transform: rotate(180deg);
    }
  }

  .hidden {
    display: none;
  }
</style>
