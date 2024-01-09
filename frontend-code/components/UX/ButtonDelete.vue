<template>
  <UXButton
    class="btnDelete"
    @mousedown="mouseDown"
    @mouseup="mouseUp"
  >
    <div class="icon">
      <IconsDelete :stroke="stroke"/>
    </div>
    <span>
      {{ tran.delete[config.lang] }}
    </span>
  </UXButton>
</template>

<script setup>
  import tran from '~/translates.json';
  const config = useConfigStore();

  let stroke = ref('#F25755');

  const mouseDown = () => stroke.value = 'white';
  const mouseUp = () => stroke.value = '#F25755';
</script>

<style lang="scss" scoped>
  @import "@/public/sass/variables.scss";
  button.btnDelete {
    color: $btn-delete-color;
    background-color: $btn-delete-bg;
    border: 1px solid $btn-delete-border;
    &:hover {
      color: $btn-delete-hover-color;
      background-color: $btn-delete-hover-bg;
      border-color: $btn-delete-hover-border;
    }
    &:active {
      color: $btn-delete-active-color;
      background-color: $btn-delete-active-bg;
      border-color: $btn-delete-active-border;
    }
    .icon {
      width: $icon-width;
      height: $icon-height;
    }
  }
</style>

