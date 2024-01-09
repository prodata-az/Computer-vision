<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    viewBox="0 0 21 20"
    :fill="fill"
  >
    <path
      d="M3 5.00008H4.66667M4.66667 5.00008H18M4.66667 5.00008V16.6667C4.66667 17.1088 4.84226 17.5327 5.15482 17.8453C5.46738 18.1578 5.89131 18.3334 6.33333 18.3334H14.6667C15.1087 18.3334 15.5326 18.1578 15.8452 17.8453C16.1577 17.5327 16.3333 17.1088 16.3333 16.6667V5.00008H4.66667ZM7.16667 5.00008V3.33341C7.16667 2.89139 7.34226 2.46746 7.65482 2.1549C7.96738 1.84234 8.39131 1.66675 8.83333 1.66675H12.1667C12.6087 1.66675 13.0326 1.84234 13.3452 2.1549C13.6577 2.46746 13.8333 2.89139 13.8333 3.33341V5.00008M8.83333 9.16675V14.1667M12.1667 9.16675V14.1667"
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
    stroke: {type: String, default: '#F25755'},
  })
</script>
