<template>
  <button
    :type="type"
    class="button"
    @click="save"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="20"
      height="20"
      viewBox="0 0 20 20"
      fill="none"
      class="relative_left icon"
    >
      <g clip-path="url(#clip0_517_6926)">
        <path d="M18.3334 9.2333V9.99997C18.3324 11.797 17.7505 13.5455 16.6745 14.9848C15.5986 16.4241 14.0862 17.477 12.3629 17.9866C10.6396 18.4961 8.7978 18.4349 7.11214 17.8121C5.42648 17.1894 3.98729 16.0384 3.00922 14.5309C2.03114 13.0233 1.56657 11.24 1.68481 9.4469C1.80305 7.65377 2.49775 5.94691 3.66531 4.58086C4.83288 3.21482 6.41074 2.26279 8.16357 1.86676C9.91641 1.47073 11.7503 1.65192 13.3918 2.3833M18.3334 3.3333L10.0001 11.675L7.50009 9.17497" stroke="#00AE65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </g>
      <defs>
        <clipPath id="clip0_517_6926">
          <rect width="20" height="20" fill="white"/>
        </clipPath>
      </defs>
    </svg>
    <span class="relative_left">{{ text }}</span>
  </button>
</template>

<script setup>
  const props = defineProps(['type', 'text']);
  const emit = defineEmits(['save']);

  const save = () => {
    emit('save');
  }
</script>

<style scoped>
  .button{
    position: relative;
    float: left;
    background: #FFF;
    padding: 10px 16px;
    border-radius: 8px;
    color: #00AE65;
    font-family: Inter;
    font-size: 14px;
    font-weight: 600;
    line-height: 20px;
    border: 1px solid #00AE65;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    cursor: pointer;
  }
  .button .icon{
    width: 20px;
    height: 20px;
    margin-right: 8px;
  }
  .button:hover{
    background: #E9FFFA;
  }
  .button:active{
    background: #32977F;
    color: #fff;
  }
  .button:active path{
    stroke: #fff;
  }
</style>
