<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    version="1.1"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    :width="width"
    :height="height"
    x="0"
    y="0"
    viewBox="0 0 512 512"
    style="enable-background: new 0 0 512 512"
    xml:space="preserve"
    class=""
  >
    <g>
      <path
        fill-rule="evenodd"
        d="M412.636 37.818c0-12.05 9.768-21.818 21.818-21.818s21.818 9.769 21.818 21.818v107.868c0 12.05-9.769 21.818-21.818 21.818H326.586c-12.05 0-21.818-9.768-21.818-21.818s9.769-21.818 21.818-21.818h61.777C352.677 89.515 305.621 70.593 256 70.593c-105.427 0-190.886 85.458-190.886 190.885S150.572 452.364 256 452.364s190.886-85.458 190.886-190.886c0-12.05 9.768-21.818 21.818-21.818s21.818 9.769 21.818 21.818C490.522 390.996 385.518 496 256 496S21.478 390.996 21.478 261.478 126.482 26.956 256 26.956c58.022 0 113.552 21.359 156.636 60.047z"
        :fill="fill"
        opacity="1"
        data-original="#000000"
        class=""
      ></path>
    </g>
  </svg>
</template>

<script setup>
  const props = defineProps({
    width: {type: String, default: '100%'},
    height: {type: String, default: '100%'},
    fill: {type: String, default: '#000000'},
  })
</script>
