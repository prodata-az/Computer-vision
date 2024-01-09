import tran from '~/translates.json';

export const useModelsStore = defineStore('modelsStore', () => {
  const user = useUser();
  const config = useConfigStore();
  const notify = useNotifyStore();

  let list = ref([]);
  let active = ref(0);
  let idModel = ref(1);
  let currentModel = ref(1);
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
      input.value = document.getElementById('addModel');
    },
    { deep: true }
  );

  const allList = async () => {
    try {
      const { data } = await $larafetch(`api/models/${user.value.id}`);

      const model = data.find(element => element.status === true);

      active.value = model.id;
      list.value = data;
    } catch (error) {
      console.error(error);
    }
  };

  const getList = async () => {
    try {
      const response = await $larafetch(
        `api/models?user_id=${user.value.id}&page=${currentPage.value}`
      );

      lastPage.value = response.meta.last_page;
      totalItems.value = response.meta.total;
      perPage.value = response.meta.per_page;
      list.value = response.data;
    } catch (error) {
      console.error(error);
    }
  };

  const add = async () => {
    try {
      let formData = new FormData();
      formData.append('name', name.value);
      formData.append('user_id', user.value.id);
      formData.append('page', currentPage.value);

      notify.loading('addModel', tran['wait'][config.lang]);

      const response = await $larafetch('api/models/store', {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      lastPage.value = response.meta.last_page;
      totalItems.value = response.meta.total;
      perPage.value = response.meta.per_page;
      list.value = response.data;

      currentPage.value = response.meta.last_page;

      name.value = '';

      notify.update('addModel', tran['added'][config.lang], 'success');
    } catch (err) {
      const { errors } = err.data;

      getErrors(errors);

      error.value.forEach((element, index) => {
        if (index === 0) notify.update('addModel', element, 'error');
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
      formData.append('user_id', user.value.id);
      formData.append('page', currentPage.value);
      formData.append('name', newName.value);
      formData.append('_method', 'PATCH');

      const response = await $larafetch(`api/models/${idModel.value}/name`, {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      lastPage.value = response.meta.last_page;
      totalItems.value = response.meta.total;
      perPage.value = response.meta.per_page;
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
      notify.loading('deleteModel', tran['wait'][config.lang]);

      const formData = new FormData();
      formData.append('user_id', user.value.id);
      formData.append('page', currentPage.value);
      formData.append('_method', 'DELETE');

      const response = await $larafetch(`api/models/${idModel.value}`, {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      if (response.meta.from === null) {
        currentPage.value = response.meta.last_page;
      } else {
        lastPage.value = response.meta.last_page;
        totalItems.value = response.meta.total;
        perPage.value = response.meta.per_page;
        list.value = response.data;
      }

      notify.update('deleteModel', tran['deleted'][config.lang], 'success');
    } catch (error) {
      notify.update(
        'deleteModel',
        tran['an_error_has_occured'][config.lang],
        'error'
      );
      console.error(error);
    }
  };

  const getName = () => {
    list.value.find((element) => {
      if (element.id === idModel.value) {
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

  const getActive = async () => {
    try {
      const formData = new FormData();
      formData.append('user_id', user.value.id);

      let response = await $larafetch('api/models/active', {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      if (Object.keys(response)) {
        response = Object.values(response)[0];
      }

      active.value = response.id;
    } catch (error) {
      console.error(error);
    }
  };

  const updateStatus = async () => {
    try {
      const formData = new FormData();
      formData.append('user_id', user.value.id);
      formData.append('id', active.value);

      const response = await $larafetch('api/models/status', {
        formdata: true,
        method: 'POST',
        body: formData,
      });
    } catch (err) {
      console.error(err);
    }
  };

  return {
    list,
    active,
    idModel,
    currentModel,
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
    allList,
    getList,
    add,
    rename,
    del,
    getName,
    cancel,
    getErrors,
    clearErrors,
    getActive,
    updateStatus,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useModelsStore, import.meta.hot));
}
