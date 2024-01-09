<template>
  <NuxtLayout name="main">
    <div class="main">
      <SelectModel />
      <section
        v-if="true"
        class="inference"
      >
        <div class="inference__container">
          <section class="inference__media">
            <div class="player">
              <div class="file-info">
                <form class="upload">
                  <UXInputUpload
                    id="upload"
                    name="upload"
                    :files="files"
                    @change="change"
                    hidden
                  />

                  <UXButton
                    @click.prevent="uploadClick"
                    type="submit"
                    name="sumbit"
                    class="btn--upload"
                  >
                    <span
                      class="btn__icon"
                      :class="{
                        'btn--loading': isLoading && !videoIsLoaded,
                      }"
                    >
                      <IconsAdd
                        :class="{
                          hidden: isLoading || videoIsLoaded,
                          display_none: videoIsLoaded,
                        }"
                      />
                      <IconsChecklist
                        :class="{ display_none: !videoIsLoaded }"
                      />
                    </span>
                    <span btn__text>
                      {{ uploadButtonText }}
                    </span>
                  </UXButton>
                  <div
                    v-if="videoIsLoaded"
                    class="file"
                  >
                    <div
                      v-if="videoFile.name"
                      class="file__container"
                    >
                      <span class="file__name">
                        {{ videoFile.name }}
                      </span>
                      <div class="file__delete">
                        <UXButton
                          name="delete"
                          class="btn--delete"
                        >
                          <IconsDelete
                            @click="deleteFile"
                            fill="red"
                          />
                        </UXButton>
                      </div>
                    </div>
                    <span class="file__description">
                      {{
                        `${videoFileWidth}x${videoFileHeight}, ${videoFileSize}`
                      }}
                    </span>
                  </div>
                </form>
              </div>
              <div class="player__container">
                <UXInputVideo
                  id="video"
                  class="player__element"
                />
                <div class="switch">
                  <div class="switch__btns">
                    <UXButton
                      name="previous"
                      @click="switch_segment"
                      :disabled="firstSegment"
                    >
                      {{ tran['previous'][lang] }}
                    </UXButton>

                    <UXButton
                      name="next"
                      @click="switch_segment"
                      :disabled="lastSegment"
                    >
                      {{ tran['next'][lang] }}
                    </UXButton>
                  </div>
                  <span
                    v-if="data.length"
                    class="switch__counter"
                  >
                    {{ `${currentSegment} / ${data.length}` }}
                  </span>
                </div>
              </div>
            </div>
            <section class="sidebar">
              <div class="sidebar__container">
                <div class="sidebar__header styled_scrollbar">
                  <div class="cluster-list">
                    <UXButton
                      v-for="(cluster, index) in clusterList"
                      :id="index"
                      :key="index"
                      @click="toSelectCluster"
                    >
                      {{ `Cluster ${Number(index) + 1}` }}
                    </UXButton>
                  </div>
                </div>
                <div
                  v-if="labelList"
                  class="sidebar__main"
                >
                  <div class="labels">
                    <div class="labels__header">  
                      <h5>
                        <span>Labels</span>
                      </h5>
                    </div>
                    <nav class="labels__main styled_scrollbar">
                      <ul class="labels-list">
                        <li
                          v-if="!showRetrain"
                          class="labels-list__items items items--active"
                        >
                          {{ labelList }}
                        </li>

                        <li
                          :id="currentClusterIndex"
                          :key="index"
                          v-if="showRetrain"
                        >
                          <div class="label-block">
                            <div
                              v-for="(row, index) in labelList"
                              :class="`label-${index + 1}`"
                            >
                              <label>
                                <input
                                  type="radio"
                                  :name="`labels_${index + 1}`"
                                  :value="row"
                                  v-model="
                                    data[currentSegment - 1].labels[
                                      currentClusterIndex
                                    ]
                                  "
                                />
                                <span>
                                  {{ row }}
                                </span>
                              </label>
                            </div>
                          </div>

                          <UXButton>
                            <IconsReset @click="toResetLabels" />
                          </UXButton>
                        </li>
                      </ul>

                      <div
                        v-if="showRetrain"
                        class="confidence-scores"
                        :class="{
                          'danger-text':
                            confidenceScores[currentClusterIndex] < 50,
                        }"
                      >
                        <span>
                          {{ `${confidenceScores[currentClusterIndex]}%` }}
                        </span>
                      </div>
                    </nav>
                  </div>
                </div>
              </div>
            </section>
          </section>
          <section class="inference__btns">
            <UXButton
              :disabled="!clusterList.length"
              @click="toChangeAndToRetrain"
            >
              {{ showChange ? 'Change' : tran['retrain'][lang] }}
            </UXButton>
          </section>

          <div
            v-if="true"
            id="chart"
          ></div>
        </div>
      </section>
    </div>
  </NuxtLayout>
</template>

<script setup>
  import tran from '~/translates.json';
  import axios from 'axios';

  const config = useConfigStore();
  const configs = useConfigsStore();

  useHead({
    script: [{ src: 'https://cdn.jsdelivr.net/npm/apexcharts' }],
  });

  const props = defineProps({
    isRetrain: { type: Boolean, default: false },
  });

  const emit = defineEmits(['toReTrain']);

  const idOfUser = 1;
  const idOfVideo = 1;

  let isLoading = ref(false);
  let lang = ref(1);
  let data = ref({});
  let showRetrain = ref(false);
  let showChange = ref(true);
  let listOfLabels = ref([]);
  let arrayOfLabels = ref([]);

  // Dynamic text for button 'Upload'
  let uploadButtonText = computed(() => {
    if (isLoading.value) {
      return videoFile.value.name;
    } else if (videoIsLoaded.value) {
      return tran['file_selected'][lang.value];
    } else {
      return tran['choose_file'][lang.value];
    }
  });

  // ***** All variables for upload video *****
  let videoIsLoaded = ref(false);
  let videoFile = ref('');
  let videoElement = '';
  let videoFileWidth = ref('');
  let videoFileHeight = ref('');
  let videoFileDuration = ref('');
  let videoFileSize = ref('');
  // ******************************************

  // ***** All variables for segments *****
  const SEGMENT_DURATION = 15;
  let currentSegment = ref(1);
  let firstSegment = computed(() => currentSegment.value === 1);
  let lastSegment = computed(() => {
    if (!data.value.length) return true;
    return currentSegment.value === data.value.length;
  });
  let startTime = ref(0);
  let endTime = ref(computed(() => startTime.value + SEGMENT_DURATION));
  let second = computed(() => data.value[currentSegment.value - 1].timestamp);
  // ***************************************

  // ***** All variables for clusters and labels *****
  let currentClusterIndex = ref('0');

  let clusterList = computed(() => {
    if (data.value.length && !showRetrain.value) {
      return data.value[currentSegment.value - 1].labels;
    }
    if (data.value.length && showRetrain.value) {
      return arrayOfLabels.value;
    }
    return [];
  });

  let labelList = computed(() => {
    if (data.value.length && !showRetrain.value) {
      return clusterList.value[currentClusterIndex.value];
    }
    if (data.value.length && showRetrain.value) {
      return clusterList.value[currentClusterIndex.value];
    }
    return '';
  });

  let confidenceScores = computed(
    () => data.value[currentSegment.value - 1].confidence_scores
  );
  // **************************************************

  // ***** All variables for chart *****
  let chart = '';

  let options = ref({
    chart: {
      width: 700,
      height: 350,
      type: 'rangeBar',
    },
    series: [],
    plotOptions: {
      bar: {
        horizontal: true,
        barHeight: '50%',
        rangeBarGroupRows: true,
      },
    },
    xaxis: {
      type: 'datetime',
      labels: {
        datetimeFormatter: {
          hour: 'hh:mm',
          minute: 'mm',
          second: 'm:ss',
        },
      },
    },
    fill: {
      type: 'solid',
    },
    legend: {
      position: 'right',
    },
    // tooltip: {
    //   custom: function(opts) {
    //     const fromYear = new Date(opts.y1).getFullYear()
    //     const toYear = new Date(opts.y2).getFullYear()
    //     const values = opts.ctx.rangeBar.getTooltipValues(opts)

    //     return (
    //       ''
    //     )
    //   }
    // },
    colors: ['#21a33f', '#a33221'],
  });

  let testData = [
    {
      timestamp: 0,
      labels: ['not interesting', 'white'],
    },
    {
      timestamp: 15,
      labels: ['interesting', 'black'],
    },
    {
      timestamp: 30,
      labels: ['not interesting', 'black'],
    },
    {
      timestamp: 45,
      labels: ['interesting', 'white'],
    },
  ];
  // ***********************************

  const uploadClick = () => {
    const inputFile = document.getElementById('upload');
    inputFile.click();
  };

  const secondsToHMS = (duration) => {
    const hours = Math.floor(duration / 3600);
    const minutes = Math.floor((duration % 3600) / 60);
    const seconds = Math.floor(duration % 60);
    let result = '';

    // check hours and add in result
    if (hours > 0) {
      result += `${hours}:`;
    }

    // check minutes and add in result
    if (minutes < 10) {
      result += `0${minutes}:`;
    }
    // check seconds and add in result
    if (seconds < 10) {
      result += `0${seconds}`;
    }

    return result;
  };

  const bytesToMb = (size) => {
    const megabytes = size / 1024 / 1024;
    return `${megabytes.toFixed(2)} Mb`;
  };

  const message = (text) => {
    Toastify({
      text: text,
      backgroundColor: '#47dc6b',
      duration: 2500,
    }).showToast();
  };

  const messageError = (text) => {
    Toastify({
      text: text,
      backgroundColor: 'red',
      duration: 2500,
    }).showToast();
  };

  const videoElementsInit = () => {
    // Add listeners for video-elment
    if (videoElement) {
      videoElement.addEventListener('timeupdate', () => {
        if (
          videoElement.currentTime < startTime.value ||
          videoElement.currentTime > endTime.value
        ) {
          videoElement.currentTime = startTime.value;
          videoElement.pause();
        }
      });
      videoElement.addEventListener('seeking', () => {
        if (
          videoElement.currentTime < startTime.value ||
          videoElement.currentTime > endTime.value
        ) {
          videoElement.currentTime = startTime.value;
        }
      });
      videoElement.onloadedmetadata = (event) => {
        videoFileWidth.value = videoElement.videoWidth;
        videoFileHeight.value = videoElement.videoHeight;
        videoFileDuration.value = videoElement.duration.toFixed(0);
        videoFileDuration.value = secondsToHMS(videoFileDuration.value);
        videoFileSize.value = bytesToMb(videoFile.value.size);
        videoIsLoaded.value = true;
      };
    }
  };

  const switch_segment = (event) => {
    if (event.target.name === 'previous') {
      if (!firstSegment.value) {
        currentSegment.value -= 1;
        startTime.value = second.value;
        videoElement.play();
      }
    } else if (event.target.name === 'next') {
      if (!lastSegment.value) {
        currentSegment.value += 1;
        startTime.value = second.value;
        videoElement.play();
      }
    }
  };

  const change = async (event) => {
    try {
      // find video element and add to it listeners
      videoElement = document.getElementById('video');
      videoElementsInit();

      isLoading.value = true; // activate the spinner
      videoFile.value = event.file;
      const formData = new FormData();

      // add video file in form-data
      formData.append('file', videoFile.value);

      // create a url of video file for mount
      const videoURL = URL.createObjectURL(videoFile.value);

      /*
       * send the form data to server,
       *
       */
      const response = await axios.post(
        `http://10.20.36.26:8000/inference/?userID=${idOfUser}&videoID=${idOfVideo}`,
        formData
      );
      // data.value = await response.data;

      toChangeCharts();

      // assign a new value to variables
      second.value = data.value[currentSegment.value - 1].timestamp;
      startTime.value = second.value;

      // mount the url of video to video elements
      videoElement.src = videoURL;

      isLoading.value = false; // deactivate the spinner
    } catch (error) {
      console.error(error);
    }
  };

  const toSelectCluster = (event) => {
    currentClusterIndex.value = event.target.id;
  };

  const deleteFile = () => {
    // get inpute with type 'file'
    const uploadButton = document.getElementById('upload');

    // if 'uploadButton' is not defined show message in console
    if (!uploadButton) return console.error('"uploadButton" is not defined');

    // reset all the values of important variables
    uploadButton.value = '';
    videoElement.src = '';
    videoIsLoaded.value = false;
    startTime.value = 0;
    data.value = [];
    showChange.value = true;
    showRetrain.value = false;
  };

  const toChangeAndToRetrain = async () => {
    try {
      if (showRetrain.value) {
        message('Labels is retrained');
      }
      if (showChange.value) {
        // send request for geting labels
        const response = await axios.post(
          `http://10.20.36.26:8000/get_confidence_score/`,
          {
            userID: idOfUser,
            videoID: idOfVideo,
            retrain: data.value,
          }
        );

        // save response in variable 'data'
        data.value = await response.data;

        // assign a new value to variables
        second.value = data.value[currentSegment.value - 1].timestamp;
        startTime.value = second.value;

        showChange.value = false;
        showRetrain.value = true;
      }
    } catch (error) {
      console.error(error);
    }
  };

  const toResetLabels = (event) => {
    const element = event.target; // get element which to click
    const li = element.closest('li'); // find the 'li' element

    if (!li) return console.error('There is not li'); // if 'li' element is not finded exit from function

    const id = String(li.id); // get id of 'li' element

    if (!id) return console.error('There is not li'); // if id is not finded exit from function

    data.value[currentSegment.value - 1].labels[id] = ''; // reset value of label
  };

  const toCreateChart = () => {
    chart = new ApexCharts(document.querySelector('#chart'), options.value);
  };

  const getLabelsOfUser = async () => {
    // to get list of labels for current user
    const { data: tableOflabels } = await axios.get(
      `http://10.20.36.16:8002/api/labels?config_id=2`
    );
    listOfLabels.value = tableOflabels.data;

    listOfLabels.value.forEach((element, index) => {
      arrayOfLabels.value[index] = [element.label_0, element.label_1];
    });
  };

  const initSeriesForChart = () => {
    let { series } = options.value;
    // add required options for chart
    arrayOfLabels.value.forEach((element) => {
      element.forEach((el) => {
        series.push({
          name: el,
          data: [
            {
              x: `${element[1]} / ${element[0]}`,
              y: [],
            },
          ],
        });
      });
    });
  };

  const toChangeCharts = () => {
    let { series } = options.value;
    // to add a data for chart
    testData.forEach((element, index) => {
      const { labels } = element;
      const { timestamp } = element;
      const step = 15000;
      const start = timestamp * 1000;
      const end = start + step;
      const interval = [start, end];

      labels.forEach((label) => {
        series.find((el) => {
          if (el.name === label) {
            let { data } = el;
            let { x } = data[0];
            let { y } = data[0];
            if (y.length) data.push({ x, y: interval });
            else data[0].y = interval;
            el.data = data;
          }
        });
      });
    });

    options.value.series = series;
     chart.render();
  };

  watch(
    () => props.isRetrain,
    (newValue) => {
      showRetrain.value = newValue;
    },
    { deep: true }
  );

  onMounted(async () => {
    await getLabelsOfUser();
    initSeriesForChart();
    toCreateChart();
  });
</script>

<style lang="scss" scoped>
  @import '~/public/sass/spinner.scss';
  @import '~/public/sass/scrollbar.scss';
  @import '~/public/sass/variables.scss';

  .display_none {
    display: none;
  }

  .styled_scrollbar {
    @include scrollbars(5px);
  }

  .container {
    max-width: 100%;
  }

  .main {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    row-gap: 24px;
  }

  .inference {
    width: 65%;
    &__container {
      display: flex;
      width: 100%;
      height: 100%;
      flex-direction: column;
      row-gap: 10px;
    }
    &__media {
      display: grid;
      max-width: 100%;
      max-height: 100%;
      grid-template-columns: 1fr 35%;
      grid-template-rows: 100%;
      grid-column-gap: 35px;
    }
    &__btns {
      display: flex;
      justify-content: flex-end;
    }
  }

  .player {
    max-width: 100%;
    display: grid;
    grid-template-columns: 100%;
    grid-template-rows: auto 1fr;
    grid-row-gap: 25px;
    &__container {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: flex-start;
    }
    &__element {
      width: 100%;
      height: 100%;
    }
  }

  .sidebar {
    width: 100%;
    height: 100%;
    &__container {
      max-width: 100%;
      height: auto;
      display: grid;
      grid-template-columns: 100%;
      grid-template-rows: 74px 1fr;
      grid-row-gap: 25px;
    }
    &__header {
      max-width: 100%;
      height: 100%;
      padding: 15px;
      overflow: auto;
      button {
        box-shadow: none;
      }
    }
    &__main {
      width: 70%;
      justify-self: center;
    }
  }

  .cluster-list {
    width: 100%;
    height: 100%;
    display: flex;
    column-gap: 10px;
  }

  .labels {
    width: 100%;
    display: flex;
    background: #3290ff47;
    border-radius: 35px;
    flex-direction: column;
    row-gap: 15px;
    padding: 15px;
    &__header {
      display: flex;
      width: 100%;
      height: 100%;
      justify-content: center;
      align-items: center;
      h5 {
        margin: 0;
      }
    }
    &__main {
      font-size: 14px;
      max-height: 231px;
      overflow: auto;
    }
  }

  .labels {
    width: fit-content;
    min-width: 250px;
    display: flex;
    background: #3290ff47;
    border-radius: 35px;
    flex-direction: column;
    row-gap: 15px;
    padding: 15px;
    &__header {
      display: flex;
      width: 100%;
      height: fit-content;
      justify-content: center;
      align-items: center;
      h5 {
        margin: 0;
      }
    }
    &__main {
      font-size: 14px;
      max-height: 231px;
      overflow: auto;
    }
    nav {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    ul {
      padding-left: 0;
      width: fit-content;
    }
    li {
      display: flex;
      align-items: center;
      justify-content: space-between;
      column-gap: 15px;
    }
    label {
      min-width: 70px;
      display: inline-flex;
      align-items: center;
      column-gap: 5px;
      cursor: pointer;
    }
    .btn {
      padding: 0;
      background: transparent;
      box-shadow: none;
      width: 15px;
      height: 15px;
    }
    .label-block {
      display: flex;
      column-gap: 10px;
    }

    @media screen and (max-width: 1000px) {
      .train {
        width: 100%;
      }
    }
  }

  .label-list {
    list-style: none;
    margin: 0;
  }

  .confidence-scores {
    font-size: 1rem;
    color: green;
  }

  .danger-text {
    color: red;
  }

  .btn {
    &--upload {
      font-size: 18px;
    }
    &__icon {
      display: inline-block;
      width: 30px;
      height: 30px;
      position: relative;
    }
    &__text {
      display: inline-block;
    }
    &--delete {
      display: inline-flex;
      width: 15px;
      height: 15px;
      padding: 0;
      border-radius: 50%;
      background-color: transparent;
      transform: translate(0px, -1px);
      &:hover {
        background-color: transparent;
        width: $icon-width;
        height: $icon-height;
      }
    }
  }

  .switch {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    &__counter {
      flex-shrink: 0;
    }
    &__btns {
      display: flex;
      column-gap: 15px;
    }
  }

  .file {
    &__container {
      display: flex;
      column-gap: 10px;
    }
  }

  .upload {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
  }
</style>
