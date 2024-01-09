import tran from '~/translates.json';

export const useInferenceStore = defineStore('inferenceStore', () => {
  const { backendUrl } = useRuntimeConfig().public;
  const config = useConfigStore();
  const configs = useConfigsStore();
  const notify = useNotifyStore();

  let file = ref('');
  let currentSegment = ref(1);
  let sessionId = ref('');
  let videoPaths = ref([]);
  let startPoints = ref([]);
  let videosIds = ref([]);
  let labelsList = ref([]);
  let resultLabels = ref([]);
  let videoLoaded = ref(false);
  let logs = ref({});
  let duration = ref(0);
  let dataForCart = ref([]);

  let videoId = computed(() => videosIds.value[currentSegment.value - 1]);

  let src = computed(() => {
    if (videoPaths.value[currentSegment.value - 1].inference === true) {
      const videoPath = videoPaths.value[currentSegment.value - 1].path;
      let path = `${backendUrl}/`;

      if (!videoPaths.value.length) return;

      path = `${path}${videoPath}`;
      return path;
    }
  });

  const getVideoPaths = async (id) => {
    try {
      const formData = new FormData();
      let videoData = [];
      formData.append('model_id', id);
      formData.append('video', file.value);

      const response = await $larafetch('/api/inference', {
        method: 'POST',
        formdata: true,
        body: formData,
      });

      duration.value = response.duration;
      videoData = Object.values(response.video_paths);

      videoData.forEach((element, idx) => {
        videoPaths.value[idx] = {
          id: element.id,
          path: element.path,
          inference: false,
        };
        videosIds.value[idx] = element.id;
      });

      startPoints.value = Object.keys(response.video_paths);
    } catch (err) {
      console.error(err);
    }
  };

  const getLabels = async () => {
    try {
      const configId = configs.currentConfig;

      const response = await $larafetch(`api/inference/${configId}`);
      labelsList.value = response;
    } catch (error) {
      console.error(error);
    }
  };

  const getHardLabels = async () => {
    try {
      sessionId.value = Math.floor(Date.now() / 1000)- 1703748542;

      const values = {
        model_id: configs.currentConfig,
        video_ids: videosIds.value,
        session_id: sessionId.value
      };

      $larafetch(`/api/inference/get_hard_labels`, {
        method: 'POST',
        body: values,
      });
    } catch (error) {
      console.error(error);
    }
  };

  const createDataForChart = () => {
    let arr = [];
    startPoints.value.forEach((element, idx) => {
      const obj = {
        timestamp: Number(element),
        labels: resultLabels.value[idx],
      };
      arr.push(obj);
    });
    dataForCart.value = arr;
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
    sessionId,
    videoPaths,
    videosIds,
    startPoints,
    videoId,
    labelsList,
    resultLabels,
    videoLoaded,
    logs,
    duration,
    dataForCart,
    getVideoPaths,
    getLabels,
    getHardLabels,
    createDataForChart,
    reset,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useInferenceStore, import.meta.hot));
}
