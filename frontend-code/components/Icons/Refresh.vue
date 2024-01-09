<template>
  <svg
    class="iconRefresh"
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    :stroke="stroke"
    viewBox="0 0 20 20"
    fill="none"
  >
    <g clip-path="url(#clip0_2193_470)">
      <path
        d="M0.833008 3.33333V8.33333M0.833008 8.33333H5.83301M0.833008 8.33333L4.69967 4.7C5.5953 3.80392 6.70332 3.14934 7.92035 2.79731C9.13738 2.44528 10.4238 2.40729 11.6594 2.68688C12.8951 2.96647 14.0398 3.55452 14.9868 4.39618C15.9337 5.23783 16.652 6.30565 17.0747 7.5M19.1663 16.6667V11.6667M19.1663 11.6667H14.1663M19.1663 11.6667L15.2997 15.3C14.4041 16.1961 13.296 16.8507 12.079 17.2027C10.862 17.5547 9.5756 17.5927 8.33991 17.3131C7.10423 17.0335 5.95951 16.4455 5.01256 15.6038C4.06562 14.7622 3.34731 13.6943 2.92467 12.5"
        stroke=""
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </g>
    <defs>
      <clipPath id="clip0_2193_470">
        <rect
          width="20"
          height="20"
          :fill="fill"
        />
      </clipPath>
    </defs>
  </svg>
</template>

<script setup>
  const props = defineProps({
    width: { type: String, default: '100%' },
    height: { type: String, default: '100%' },
    fill: { type: String, default: 'white' },
    stroke: { type: String, default: '#00AE65' },
  });
</script>
