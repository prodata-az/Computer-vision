<template>
  <UXButton
    class="btnSave"
    @mousedown="mouseDown"
    @mouseup="mouseUp"
  >
    <div class="icon">
      <IconsSuccess :fill="fill" :stroke="stroke"/>
    </div>
    {{ tran['save'][config.lang] }}
  </UXButton>
</template>

<script setup>
  import tran from '~/translates.json';

  const config = useConfigStore();
  let fill = ref('white');
  let stroke = ref('#00AE65');

  const mouseDown = () => {
    fill.value = '#00AE65';
    stroke.value = 'white';
  }

  const mouseUp = () => {
    fill.value = 'white';
    stroke.value = '#00AE65';
  }
</script>

<style lang="scss" scoped>
  @import "@/public/sass/variables.scss";
  button.btnSave {
    color: $btn-save-color;
    background-color: $btn-save-bg;
    border: 1px solid $btn-save-border;
    &:hover {
      color: $btn-save-hover-color;
      background-color: $btn-save-hover-bg;
      border-color: $btn-save-hover-border;
    }
    &:active {
      color: $btn-save-active-color;
      background-color: $btn-save-active-bg;
      border-color: $btn-save-active-border;
    }
    .icon {
      width: $icon-width;
      height: $icon-height;
    }
  }
</style>

