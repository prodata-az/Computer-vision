<template>
  <button
    :type="type"
    class="button flex_align_center"
    @click="click"
  >
    <img class="relative_left icon" :src="icon_path"/>
    <span class="relative_left text">{{ text }}</span>
  </button>
</template>

<script setup>
  const props = defineProps(['text', 'icon_path', 'type']);
  const emit = defineEmits(['click']);

  const click = () => {
    emit('click');
  }
</script>

<style scoped>
  .button{
    border-radius: 8px;
    border: 1px solid #D0D5DD;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 16px;
  }
  .button:hover{
    background: #F9FAFB;
  }
  .button:active{
    background: #EBEBEB;
  }
  .text{
    color: #344054;
    font-family: Inter;
    font-size: 14px;
    font-weight: 600;
    line-height: 20px;
  }
  .icon{
    width: 20px;
    height: 20px;
    object-fit: scale-down;
    margin-right: 8px;
  }
</style>
