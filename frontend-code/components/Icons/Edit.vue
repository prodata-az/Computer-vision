<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    viewBox="0 0 21 20"
    :fill="fill"
  >
    <path
      d="M9.66699 3.33344H3.83366C3.39163 3.33344 2.96771 3.50904 2.65515 3.8216C2.34259 4.13416 2.16699 4.55808 2.16699 5.00011V16.6668C2.16699 17.1088 2.34259 17.5327 2.65515 17.8453C2.96771 18.1578 3.39163 18.3334 3.83366 18.3334H15.5003C15.9424 18.3334 16.3663 18.1578 16.6788 17.8453C16.9914 17.5327 17.167 17.1088 17.167 16.6668V10.8334M15.917 2.08344C16.2485 1.75192 16.6982 1.56567 17.167 1.56567C17.6358 1.56567 18.0855 1.75192 18.417 2.08344C18.7485 2.41496 18.9348 2.8646 18.9348 3.33344C18.9348 3.80228 18.7485 4.25192 18.417 4.58344L10.5003 12.5001L7.16699 13.3334L8.00033 10.0001L15.917 2.08344Z"
      :stroke="stroke"
      stroke-width="1.66667"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<script setup>
  const props = defineProps({
    width: {type: String, default: '100%'},
    height: {type: String, default: '100%'},
    fill: {type: String, default: 'none'},
    stroke: {type: String, default: '#00A3FF'},
  })
</script>
