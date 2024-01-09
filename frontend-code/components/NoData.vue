<template>
  <div class="no-data" :style="`width: ${width}; height: ${height};`">
    <span>{{ tran.noData[config.lang] }}</span>
  </div>
</template>

<script setup>
import tran from '~/translates.json';

const config = useConfigStore();

const props = defineProps({
  width: { type: String, default: '100%' },
  height: { type: String, default: '100%' }
})

</script>

<style lang="scss" scoped>
.no-data {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1rem;
  font-weight: 600;
  span {
    opacity: 0.5;
  }
}
</style>