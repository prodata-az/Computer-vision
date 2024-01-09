<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :class="class"
    :width="width"
    :height="height"
    viewBox="0 0 25 24"
    fill="none"
    stroke="black"
    stroke-opacity="0.38"
  >
    <path
      d="M21.9403 11.05L12.7503 20.24C11.6244 21.3659 10.0975 21.9984 8.50529 21.9984C6.91311 21.9984 5.38613 21.3659 4.26029 20.24C3.13445 19.1142 2.50195 17.5872 2.50195 15.995C2.50195 14.4029 3.13445 12.8759 4.26029 11.75L13.4503 2.56004C14.2009 1.80948 15.2188 1.38782 16.2803 1.38782C17.3417 1.38782 18.3597 1.80948 19.1103 2.56004C19.8609 3.3106 20.2825 4.32859 20.2825 5.39004C20.2825 6.4515 19.8609 7.46948 19.1103 8.22004L9.91029 17.41C9.53501 17.7853 9.02602 17.9962 8.49529 17.9962C7.96456 17.9962 7.45557 17.7853 7.08029 17.41C6.70501 17.0348 6.49418 16.5258 6.49418 15.995C6.49418 15.4643 6.70501 14.9553 7.08029 14.58L15.5703 6.10004"
      stroke=""
      stroke-opacity=""
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<script setup>
  const props = defineProps({
    class: { type: String, default: '' },
    width: { type: String, default: '100%' },
    height: { type: String, default: '100%' },
  });
</script>