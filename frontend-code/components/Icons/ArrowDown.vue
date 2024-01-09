<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    :stroke="stroke"
    :fill="fill"
    viewBox="0 0 20 20"
  >
    <path
      d="M5 7.5L10 12.5L15 7.5"
      stroke=""
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<script setup>
  const props = defineProps({
    width: {type: String, default: '100%'},
    height: {type: String, default: '100%'},
    fill: {type: String, default: '#FFFFFF'},
    stroke: {type: String, default: '#101828'},
  })
</script>