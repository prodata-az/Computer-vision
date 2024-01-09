<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    viewBox="0 0 20 20"
    fill="none"
  >
    <g clip-path="url(#clip0_1076_3182)">
      <path
        d="M9.99984 6.66675V13.3334M6.6665 10.0001H13.3332M18.3332 10.0001C18.3332 14.6025 14.6022 18.3334 9.99984 18.3334C5.39746 18.3334 1.6665 14.6025 1.6665 10.0001C1.6665 5.39771 5.39746 1.66675 9.99984 1.66675C14.6022 1.66675 18.3332 5.39771 18.3332 10.0001Z"
        :stroke="stroke"
        stroke-width="1.67"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </g>
    <defs>
      <clipPath id="clip0_1076_3182">
        <rect width="20" height="20" :fill="fill" />
      </clipPath>
    </defs>
  </svg>
</template>

<script setup>
const props = defineProps({
  width: { type: String, default: "100%" },
  height: { type: String, default: "100%" },
  fill: { type: String, default: "#FFFFFF" },
  stroke: { type: String, default: "#1D2939" },
});
</script>
