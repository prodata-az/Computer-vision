import tran from '~/translates.json';

export const useTrainStore = defineStore('trainStore', () => {
  const { backendUrl } = useRuntimeConfig().public;
  const config = useConfigStore();
  const configs = useConfigsStore();
  const notify = useNotifyStore();

  let file = ref('');
  let currentSegment = ref(1);
  let videoPaths = ref([]);
  let startPoints = ref([]);
  let videosIds = ref([]);
  let labelsList = ref([]);
  let videoLoaded = ref(false);

  let videoId = computed(() => videosIds.value[currentSegment.value - 1]);

  let src = computed(() => {
    const videoPath = videoPaths.value[currentSegment.value - 1];
    let path = `${backendUrl}/`;

    if (!videoPaths.value.length) return;

    path = `${path}${videoPath}`;
    return path;
  });

  const getVideoPaths = async () => {
    const formData = new FormData();
    let videoData = [];
    formData.append('model_id', configs.currentConfig);
    formData.append('video', file.value);

    const response = await $larafetch('/api/train', {
      method: 'POST',
      formdata: true,
      body: formData,
    });

    videoData = Object.values(response);

    videoData.forEach((element, idx) => {
      videoPaths.value[idx] = element.path;
      videosIds.value[idx] = element.id;
    });

    startPoints.value = Object.keys(response);
  };

  const getLabels = async () => {
    try {
      const configId = configs.currentConfig;

      const response = await $larafetch(`api/train/${configId}`);
      labelsList.value = response;
    } catch (error) {
      console.error(error);
    }
  };

  const reset = () => {
    file.value = '';
    currentSegment.value = 1;
    videoPaths.value = [];
    startPoints.value = [];
    labelsList.value = [];
    videoLoaded.value = false;

    notify.success(tran.successfully_deleted[config.lang]);
  };

  return {
    file,
    src,
    currentSegment,
    videoPaths,
    videosIds,
    startPoints,
    videoId,
    labelsList,
    videoLoaded,
    getVideoPaths,
    getLabels,
    reset,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useTrainStore, import.meta.hot));
}
