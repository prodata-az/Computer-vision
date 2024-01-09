<template>
  <div
    class="main"
    :style="`z-index: ${ show ? '101' : '-1' };
             opacity: ${ show ? '1' : '0' };
             -webkit-transition: ${ show ? 'opacity' : 'all' } .15s linear;
             transition: ${ show ? 'opacity' : 'all' } .15s linear;`"
  >
    <div
      class="relative_100 delete_block_div"
      :style="`transform: ${ show ? 'translate(0,0)' : 'translate(0,-25%)' }`"
    >
      <div class="delete_block">
        <span class="relative_100 delete_title">
          {{ tran.deleting[config.lang] }}
        </span>
        <span class="relative_100 delete_message">{{ tran.are_you_sure_to_delete[config.lang] }}?</span>
        <div class="relative_100 delete_block_buttons">
          <UXButton
            @click="close_func"
          >
            {{ tran.cancel[config.lang] }}
          </UXButton>
          <UXButtonDelete 
            @click="delete_func"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import tran from '~/translates.json';

  const config = useConfigStore();
  const props = defineProps(['show']);
  const emit = defineEmits(['close', 'delete']);
	const close_func = () => emit('close')
  const delete_func = () => emit('delete')

  const enter_click_func = (e) => {
    if (e.key == "Enter" && props.show) {
      e.preventDefault();
      delete_func();
    }
  }

  onMounted(() => {
    window.addEventListener('keydown', enter_click_func);
  });

  onUnmounted(() => {
     window.removeEventListener("keydown", enter_click_func);
  });
</script>


<style scoped>
  .main{
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    background: #00000099;
  }
  .delete_block_div{
    display: flex;
    justify-content: center;
    margin-top: 22px;
    -webkit-transition: -webkit-transform .3s ease-out;
    transition: -webkit-transform .3s ease-out;
    transition: transform .3s ease-out;
    transition: transform .3s ease-out, -webkit-transform .3s ease-out;
  }
  .delete_block{
    width: 600px;
    background-color: #fff;
    border-radius: 4px;
    -webkit-box-shadow: 0 0 15px 1px rgb(69 65 78 / 20%);
    box-shadow: 0 0 15px 1px rgb(69 65 78 / 20%);
    overflow: hidden;
  }
  .delete_title{
    padding: 20px 24px 19px 24px;
    color: #101828;
    font-size: 18px;
    line-height: 28px;
    border-bottom: 1px solid #EAECF0;
  }
  .delete_title i{
    position: relative;
    margin-right: 5px;
    color: #f4516c;
  }
  .delete_message{
    color: rgba(0, 0, 0, 0.60);
    padding: 10px 20px;
    font-size: 18px;
    line-height: 28px;
  }
  .delete_block_buttons{
    display: flex;
    justify-content: flex-end;
    padding: 16px 20px;
    column-gap: 12px;
  }
 
</style>
