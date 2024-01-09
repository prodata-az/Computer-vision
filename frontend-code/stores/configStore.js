export const useConfigStore = defineStore('configStore', () => {
  const lang = ref(1);

  const toChangeLang = (id) => {
    lang.value = id;
  };

  return {
    lang,
    toChangeLang,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useConfigStore, import.meta.hot));
}