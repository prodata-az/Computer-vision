import { defineStore } from 'pinia';

export const useStore = defineStore('counter', () => {
  let header_data = ref(false);
  let content_data = ref(false);

  function update_header_data(val) {
    header_data.value = val;
  }

  function update_content_data(val) {
    content_data.value = val;
  }

  return {
    header_data,
    update_header_data,
    content_data,
    update_content_data,
  };
});
