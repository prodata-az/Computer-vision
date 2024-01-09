<template>
  <div
    class="btn"
    @click="close"
  >{{ text }}</div>
</template>

<script setup>
  const props = defineProps(['text']);
  const emit = defineEmits(['close']);

  const close = () => {
    emit('close');
  }
</script>

<style scoped>
  .btn{
    border-radius: 8px;
    border: 1px solid #D0D5DD;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 8px 14px;
    color: #344054;
    font-family: Inter;
    font-size: 14px;
    font-weight: 500;
    line-height: 20px;
  }
  .btn:hover{
    background: #F9FAFB;
  }
  .btn:active{
    background: #EBEBEB;
  }
</style>
