<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    viewBox="0 0 20 20"
    :fill="fill"
  >
    <path
      d="M4.16699 9.99996H15.8337M15.8337 9.99996L10.0003 4.16663M15.8337 9.99996L10.0003 15.8333"
      :stroke="stroke"
      stroke-width="1.67"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<script setup>
  const props = defineProps({
    width: { type: String, default: "100%" },
    height: { type: String, default: "100%" },
    fill: { type: String, default: "none" },
    stroke: { type: String, default: "#344054" },
  });
</script>
