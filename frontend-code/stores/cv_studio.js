import { defineStore } from 'pinia';

export const useStore = defineStore('counter', () => {
  let projects = ref([
    {
      id: 1,
      name: 'Face Recognition',
      component: 'CVStudioFaceRecognition',
      active: false,
    },
  ]);

  let active_page_id = ref(0);

  const change_active_page = (id) => {
    active_page_id.value = id;
    projects.value = projects.value.map((row) => {
      if (row.id == id) {
        row.active = true;
      } else {
        row.active = false;
      }

      return row;
    });
  };

  return {
    projects,
    active_page_id,
    change_active_page,
  };
});
