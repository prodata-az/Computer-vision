import tran from '~/translates.json';

export const useConfigsStore = defineStore('configsStore', () => {
  const config = useConfigStore();
  const notify = useNotifyStore();
  const model = useModelsStore();

  let list = ref([]);
  let active = ref(0);
  let idConfig = ref(1);
  let currentConfig = ref(1);
  let currentPage = ref(1);
  let perPage = ref(1);
  let lastPage = ref(1);
  let totalItems = ref(1);
  let showRename = ref(false);
  let name = ref('');
  let oldName = ref('');
  let newName = ref('');
  let error = ref([]);
  let input = ref('');
  let confidenceScore = ref([]);

  watch(
    () => currentPage.value,
    () => {
      getList();
    },
    { deep: true }
  );

  watch(
    () => newName.value,
    () => {
      input.value = document.getElementById('rename');
    },
    { deep: true }
  );

  watch(
    () => name.value,
    () => {
      input.value = document.getElementById('addConfig');
    },
    { deep: true }
  );

  const getList = async () => {
    try {
      const modelId = model.currentModel;

      const response = await $larafetch(
        `api/configs?model_id=${modelId}&page=${currentPage.value}`
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
      const confidenceScore = JSON.stringify({
        medium_max: 60,
        medium_min: 40,
      });
      formData.append('model_id', model.currentModel);
      formData.append('page', currentPage.value);
      formData.append('name', name.value);
      formData.append('confidence_score', confidenceScore);

      notify.loading('addConfig', tran['wait'][config.lang]);

      const response = await $larafetch('api/configs/store', {
        formdata: true,
        method: 'post',
        body: formData,
      });

      lastPage.value = response.last_page;
      totalItems.value = response.total;
      perPage.value = response.per_page;
      list.value = response.data;

      currentPage.value = response.last_page;

      name.value = '';

      notify.update('addConfig', tran['added'][config.lang], 'success');
    } catch (err) {
      const { errors } = err.data;

      getErrors(errors);

      error.value.forEach((element, index) => {
        if (index === 0) notify.update('addConfig', element, 'error');
        else notify.error(element);
      });

      input.value.classList.add('is-invalid');

      console.error(err);
    }
  };

  const rename = async () => {
    try {
      if (newName.value.trim() === oldName.value)
        return (showRename.value = false);

      notify.loading('renameModel', tran['wait'][config.lang]);

      const formData = new FormData();
      formData.append('model_id', model.currentModel);
      formData.append('page', currentPage.value);
      formData.append('name', newName.value);
      formData.append('_method', 'PATCH');

      const response = await $larafetch(`api/configs/${idConfig.value}/name`, {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      lastPage.value = response.last_page;
      totalItems.value = response.total;
      perPage.value = response.per_page;
      list.value = response.data;

      showRename.value = false;

      notify.update('renameModel', tran['saved'][config.lang], 'success');
    } catch (err) {
      const { errors } = err.data;

      getErrors(errors);

      error.value.forEach((element, index) => {
        if (index === 0) notify.update('renameModel', element, 'error');
        else notify.error(element);
      });

      input.value.classList.add('is-invalid');

      console.error(err);
    }
  };

  const del = async () => {
    try {
      notify.loading('deleteConfig', tran['wait'][config.lang]);

      const formData = new FormData();
      formData.append('model_id', model.currentModel);
      formData.append('page', currentPage.value);
      formData.append('_method', 'DELETE');

      const response = await $larafetch(`api/configs/${idConfig.value}`, {
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

      notify.update('deleteConfig', tran['deleted'][config.lang], 'success');
    } catch (error) {
      notify.update(
        'deleteConfig',
        tran['an_error_has_occured'][config.lang],
        'error'
      );
      console.error(error);
    }
  };

  const getName = () => {
    list.value.find((element) => {
      if (element.id === idConfig.value) {
        newName.value = element.name;
        oldName.value = element.name;
      }
    });
  };

  const cancel = () => {
    newName.value = oldName.value;
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
    input.value.classList.remove('is-invalid');
  };

  const getConfidenceScore = async () => {
    const response = await $larafetch(
      `api/configs/${currentConfig.value}/confidence_score`
    );
    confidenceScore.value = [];
    confidenceScore.value.push(response['medium_min']);
    confidenceScore.value.push(response['medium_max']);
  };

  const setConfidenceScore = async (min, max) => {
    const formData = new FormData();

    formData.append('_method', 'PATCH');
    formData.append('min', min);
    formData.append('max', max);

    await $larafetch(`api/configs/${currentConfig.value}/confidence_score`, {
      method: 'POST',
      formdata: true,
      body: formData,
    });
  };

  return {
    list,
    active,
    idConfig,
    currentConfig,
    currentPage,
    perPage,
    lastPage,
    totalItems,
    showRename,
    name,
    oldName,
    newName,
    error,
    input,
    confidenceScore,
    getList,
    add,
    rename,
    del,
    getName,
    cancel,
    getErrors,
    clearErrors,
    getConfidenceScore,
    setConfidenceScore,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useConfigsStore, import.meta.hot));
}
