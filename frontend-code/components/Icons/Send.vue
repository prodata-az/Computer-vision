<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    viewBox="0 0 20 20"
    fill="none"
  >
    <g clip-path="url(#clip0_1076_3158)">
      <path
        d="M18.3332 1.66675L9.1665 10.8334M18.3332 1.66675L12.4998 18.3334L9.1665 10.8334M18.3332 1.66675L1.6665 7.50008L9.1665 10.8334"
        :stroke="stroke"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </g>
    <defs>
      <clipPath id="clip0_1076_3158">
        <rect width="20" height="20" :fill="fill" />
      </clipPath>
    </defs>
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

