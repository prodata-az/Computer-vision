<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    :fill="fill"
    :stroke="stroke"
    viewBox="0 0 20 20"
  >
    <path
      d="M16.6667 5L7.50004 14.1667L3.33337 10"
      stroke=""
      stroke-width="1.66667"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<script setup>
  const props = defineProps({
    width: { type: String, default: '100%' },
    height: { type: String, default: '100%' },
    fill: { type: String, default: 'none' },
    stroke: { type: String, default: '#7F56D9' },
  });
</script>
