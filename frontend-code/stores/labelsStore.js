import tran from '~/translates.json';

export const useLabelsStore = defineStore('labelsStore', () => {
  const notify = useNotifyStore();
  const config = useConfigStore();
  const configs = useConfigsStore();
  const model = useModelsStore();
  const labels = useLabelsStore();

  let list = ref([]);
  let idLabels = ref(1);
  let label_0 = ref('');
  let label_1 = ref('');
  let currentPage = ref(1);
  let perPage = ref(1);
  let lastPage = ref(1);
  let totalItems = ref(1);
  let showRename = ref(false);
  let oldNameLabel_0 = ref('');
  let newNameLabel_0 = ref('');
  let oldNameLabel_1 = ref('');
  let newNameLabel_1 = ref('');
  let error = ref([]);
  let input = ref('');

  watch(
    () => currentPage.value,
    () => {
      getList();
    },
    { deep: true }
  );

  const getList = async () => {
    try {
      const configId = configs.currentConfig;

      const response = await $larafetch(
        `api/labels?config_id=${configId}&page=${currentPage.value}`
      );

      lastPage.value = response.last_page;
      totalItems.value = response.total;
      perPage.value = response.per_page;
      list.value = response.data;
    } catch (error) {
      console.error(error);
    }
  };

  const add = async () => {
    try {
      const formData = new FormData();
      formData.append('page', currentPage.value);
      formData.append('config_id', configs.currentConfig);
      formData.append('label_0', label_0.value);
      formData.append('label_1', label_1.value);

      notify.loading('addLabels', tran['wait'][config.lang]);

      const response = await $larafetch('api/labels/store', {
        formdata: true,
        method: 'post',
        body: formData,
      });

      lastPage.value = response.last_page;
      totalItems.value = response.total;
      perPage.value = response.per_page;
      list.value = response.data;

      currentPage.value = response.last_page;

      label_0.value = '';
      label_1.value = '';

      notify.update('addLabels', tran['added'][config.lang], 'success');
    } catch (err) {
      const { errors } = err.data;

      getErrors(errors);

      error.value.forEach((element, index) => {
        if (index === 0) notify.update('addLabels', element, 'error');
        else notify.error(element);
      });

      const keys = Object.keys(errors);
      keys.forEach((element) => {
        const input = document.getElementById(`add_${element}`);
        if (input) input.classList.add('is-invalid');
      });

      console.error(err);
    }
  };

  const rename = async () => {
    try {


      notify.loading('renameLabels', tran['wait'][config.lang]);

      const formData = new FormData();
      formData.append('_method', 'PATCH');
      formData.append('config_id', configs.currentConfig);
      formData.append('page', currentPage.value);

      if (oldNameLabel_0.value.trim() !== newNameLabel_0.value.trim()) {
        formData.append('label_0', newNameLabel_0.value);
      }

      if (oldNameLabel_1.value.trim() !== newNameLabel_1.value.trim()) {
        formData.append('label_1', newNameLabel_1.value);
      }

      const response = await $larafetch(`api/labels/${idLabels.value}/name`, {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      lastPage.value = response.last_page;
      totalItems.value = response.total;
      perPage.value = response.per_page;
      list.value = response.data;

      showRename.value = false;

      notify.update('renameLabels', tran['saved'][config.lang], 'success');
    } catch (err) {
      const { errors } = err.data;

      getErrors(errors);

      error.value.forEach((element, index) => {
        if (index === 0) notify.update('renameLabels', element, 'error');
        else notify.error(element);
      });

      const keys = Object.keys(errors);
      keys.forEach((element) => {
        const input = document.getElementById(`rename_${element}`);
        if (input) input.classList.add('is-invalid');
      });

      console.error(err);
    }
  };

  const del = async () => {
    try {
      notify.loading('deleteLabels', tran['wait'][config.lang]);

      const formData = new FormData();
      formData.append('config_id', configs.currentConfig);
      formData.append('page', currentPage.value);
      formData.append('_method', 'DELETE');

      const response = await $larafetch(`api/labels/${idLabels.value}`, {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      if (response.from === null) {
        currentPage.value = response.last_page;
      }

      lastPage.value = response.last_page;
      totalItems.value = response.total;
      perPage.value = response.per_page;
      list.value = response.data;

      notify.update('deleteLabels', tran['deleted'][config.lang], 'success');
    } catch (error) {
      notify.update(
        'deleteLabels',
        tran['an_error_has_occured'][config.lang],
        'error'
      );
      console.error(error);
    }
  };

  const getName = () => {
    list.value.find((element) => {
      if (element.id === idLabels.value) {
        newNameLabel_0.value = element.label_0;
        oldNameLabel_0.value = element.label_0;
        newNameLabel_1.value = element.label_1;
        oldNameLabel_1.value = element.label_1;
      }
    });
  };

  const cancel = () => {
    newNameLabel_0.value = oldNameLabel_0.value;
    newNameLabel_1.value = oldNameLabel_1.value;
    clearErrors();
  };

  const getErrors = (errors) => {
    for (let key in errors) {
      let values = errors[key];
      values.forEach((element) => {
        error.value.push(element);
      });
    }
  };

  const clearErrors = () => {
    error.value = [];
    const inputs = document.querySelectorAll('.is-invalid');
    inputs.forEach((element) => {
      element.classList.remove('is-invalid');
    });
  };

  return {
    list,
    idLabels,
    label_0,
    label_1,
    currentPage,
    perPage,
    lastPage,
    totalItems,
    showRename,
    oldNameLabel_0,
    newNameLabel_0,
    oldNameLabel_1,
    newNameLabel_1,
    error,
    input,
    getList,
    add,
    rename,
    del,
    getName,
    cancel,
    clearErrors,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useLabelsStore, import.meta.hot));
}
