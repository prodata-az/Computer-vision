<template>
  <NuxtLayout name="main">
    <div class="main">
      <TitleComp />
      <div
        v-if="!isUpload"
        class="wrapper"
      >
        <main>
          <div class="wrapper__container">
            <div class="wrapper__icon">
              <img
                src="~/public/images/cloud.png"
                alt="Dron icon"
              />
            </div>
            <div class="wrapper__content">
              <div class="wrapper__title">Start by uploading a file</div>
              <div class="wrapper__description">
                Any assets used in projects will live here.Start creating by
                uploading your files.
              </div>
              <div class="wrapper__button">
                <UXButton
                  class="btn"
                  @click="clickUpload('upload')"
                >
                  <span class="btn__icon">
                    <IconsUpload />
                  </span>
                  <span> {{ tran.upload[config.lang] }} </span>
                </UXButton>
              </div>
            </div>
          </div>
          <form
            class="upload"
            action=""
          >
            <UXInputUpload
              id="upload"
              name="upload"
              @change="change"
              hidden
            />
          </form>
        </main>
      </div>
      <ControlSection v-if="isUpload">
        <template v-slot:main>
          <section class="train">
            <div class="train__container">
              <div class="video-container">
                <div class="video-container__block">
                  <div class="video-container__parts">
                    <div class="first-item">
                      <Container class="for-paths">
                        <div
                          v-if="inference.videoLoaded"
                          class="videos-list"
                        >
                          <div class="videos-list__header">
                            <h5>{{ tran.videos[config.lang] }}</h5>
                          </div>
                          <div class="table paths-table styled_scrollbar">
                            <div class="videos-list__body">
                              <ul class="">
                                <li
                                  v-for="(item, idx) in inference.videoPaths"
                                  :class="{
                                    disabled: item.inference === false,
                                  }"
                                  :key="idx"
                                  @click="inference.currentSegment = idx + 1"
                                >
                                  <div class="icon">
                                    <IconsRadioActive
                                      v-if="
                                        inference.currentSegment === idx + 1 &&
                                        item.inference === true
                                      "
                                    />
                                    <IconsRadio v-else />
                                  </div>

                                  <div v-if="item.inference === false">
                                    {{ `${tran.pending[config.lang]}...` }}
                                  </div>
                                  <div v-else-if="item.inference === true">
                                    {{ `Part ${idx + 1}` }}
                                  </div>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <NoData v-else />
                      </Container>
                    </div>
                    <div class="second-item">
                      <Container>
                        <div class="player">
                          <UXInputVideo
                            id="video"
                            :src="inference.videoLoaded ? inference.src : ''"
                          />
                        </div>
                      </Container>
                    </div>
                    <div class="third-item">
                      <Container class="for-labels">
                        <div
                          v-if="inference.videoLoaded"
                          class="labels"
                        >
                          <div class="labels__header">
                            <h5>
                              <span>{{ tran.labels[config.lang] }}</span>
                            </h5>
                          </div>
                          <div class="table labels-table styled_scrollbar">
                            <table class="labels__main styled_scrollbar">
                              <tbody
                                id="labels-list"
                                class="labels-list"
                              >
                                <tr
                                  v-if="!isRetrain"
                                  v-for="(label, idx) in inference.resultLabels[
                                    inference.currentSegment - 1
                                  ]"
                                >
                                  <td>
                                    {{ label }}
                                  </td>
                                </tr>
                                <tr
                                  v-else
                                  v-for="(label, index) in inference.labelsList"
                                >
                                  <td>
                                    <div class="label-1">
                                      <label>
                                        <div class="labels__icon">
                                          <IconsChecklistActive
                                            v-if="
                                              all_values[
                                                inference.currentSegment - 1
                                              ].labels[index] == label[0]
                                            "
                                          />
                                          <IconsChecklist v-else />
                                        </div>
                                        <input
                                          type="radio"
                                          :name="`labels_${index}`"
                                          :value="label[0]"
                                          v-model="
                                            all_values[
                                              inference.currentSegment - 1
                                            ].labels[index]
                                          "
                                        />
                                        <span>
                                          {{ label[0] }}
                                        </span>
                                      </label>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="label-2">
                                      <label>
                                        <div class="labels__icon">
                                          <IconsChecklistActive
                                            v-if="
                                              all_values[
                                                inference.currentSegment - 1
                                              ].labels[index] == label[1]
                                            "
                                          />
                                          <IconsChecklist v-else />
                                        </div>
                                        <input
                                          type="radio"
                                          :name="`labels_${index}`"
                                          :value="label[1]"
                                          v-model="
                                            all_values[
                                              inference.currentSegment - 1
                                            ].labels[index]
                                          "
                                        />
                                        <span>
                                          {{ label[1] }}
                                        </span>
                                      </label>
                                    </div>
                                  </td>
                                  <td>
                                    <UXButton>
                                      <IconsReset @click="toResetLabels" />
                                    </UXButton>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <NoData v-else />
                      </Container>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </template>
        <template v-slot:second-btn>
          <UXButton class="btn">
            <div class="btn__icon">
              <IconsUpload stroke="#000" />
            </div>
            <span>
              {{ tran.replace_file[config.lang] }}
            </span>
          </UXButton>
        </template>
        <template v-slot:third-btn>
          <UXButton
            class="btn btn-delete"
            @click="deleteFile"
          >
            <div class="btn__icon">
              <IconsDelete stroke="#fff" />
            </div>
          </UXButton>
        </template>
        <template v-slot:fourth-btn>
          <UXButton
            v-if="!isRetrain"
            class="btn-train"
            @click="isRetrain = true"
          >
            <span class="btn-train__icon">
              <IconsRefresh
                class="icon-refresh"
                stroke="#FFFFFF"
              />
            </span>
            <span>
              {{ tran.change[config.lang] }}
            </span>
          </UXButton>
          <UXButton
            v-else
            class="btn-train"
            @click="retrainModel"
          >
            <span class="btn-train__icon">
              <IconsRefresh
                class="icon-refresh"
                stroke="#FFFFFF"
              />
            </span>
            <span>
              {{ tran.retrain[config.lang] }}
            </span>
          </UXButton>
        </template>
        <Popup />
      </ControlSection>
    </div>
  </NuxtLayout>
</template>

<script setup>
  // definePageMeta({ middleware: ['auth'] });

  import tran from '~/translates.json';

  useHead({
    script: [
      { src: 'https://cdn.jsdelivr.net/npm/apexcharts' },
      { src: 'https://js.pusher.com/8.2.0/pusher.min.js' },
    ],
  });

  let isUpload = ref(false);

  const config = useConfigStore();
  const notify = useNotifyStore();
  const configs = useConfigsStore();
  const inference = useInferenceStore();
  const { id } = useRoute().params;

  configs.currentConfig = id;

  let arrayForCheck = ref([]);
  let arrayOfRequiredLabels = ref([]);
  let all_values = ref([]);
  let data = ref({});
  let isDragging = ref(false);
  let isRetrain = ref(false);

  let currentClusterIndex = ref(0);

  let clusterList = computed(() => {
    if (inference.resultLabels.length && !isRetrain.value) {
      return inference.resultLabels[inference.currentSegment - 1];
    }
    if (inference.resultLabels.length && isRetrain.value) {
      return inference.labelsList;
    }
    return [];
  });

  let labelList = computed(() => {
    if (inference.resultLabels.length && !isRetrain.value) {
      return clusterList.value[currentClusterIndex.value];
    }
    if (inference.resultLabels.length && isRetrain.value) {
      return clusterList.value[currentClusterIndex.value];
    }
    return '';
  });

  // ***** All variables for chart *****
  let chart = '';

  let options = ref({
    chart: {
      width: '100%',
      height: '100%',
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
    tooltip: {
      custom: function ({ series, seriesIndex, dataPointIndex, w }) {
        const date = new Date(series[seriesIndex][dataPointIndex]);
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const seconds = date.getSeconds();
        let result = '';
        // result += hours ? `${hours}:`: '';
        result += `${minutes}:`;
        result += seconds;
        return result;
      },
    },
    colors: ['#21a33f', '#a33221'],
  });
  // ***********************************

  // ***** All variables for upload video *****
  let videoFile = ref('');
  let videoElement = '';
  let videoFileWidth = ref('');
  let videoFileHeight = ref('');
  let videoFileDuration = ref('');
  let videoFileSize = ref('');

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

  const videoElementsInit = () => {
    if (videoElement) {
      videoElement.onloadedmetadata = (event) => {
        videoFileWidth.value = videoElement.videoWidth;
        videoFileHeight.value = videoElement.videoHeight;
        videoFileDuration.value = videoElement.duration.toFixed(0);
        videoFileDuration.value = secondsToHMS(videoFileDuration.value);
        videoFileSize.value = bytesToMb(videoFile.value.size);
      };
    }
  };

  const clickUpload = (id) => {
    try {
      const upload = document.getElementById(id);
      upload.click();
    } catch (err) {
      console.error(err);
    }
  };

  const change = async (event) => {
    try {
      isUpload.value = true;

      notify.loading('uploadVideo', tran['videoIsLoading'][config.lang]);
      videoElement = document.getElementById('video');

      await inference.getLabels();

      inference.file = event.file;
      await inference.getVideoPaths(configs.currentConfig);

      await inference.getHardLabels();

      inference.videosIds.forEach((element) => {
        data.value[element] = {};
        inference.labelsList.forEach((el, idx) => {
          data.value[element][el[2]] = {
            label_id: -1,
            is_hard_label: false,
          };
        });
      });

      inference.videoLoaded = true;

      notify.update(
        'uploadVideo',
        tran['videoUploaded'][config.lang],
        'success'
      );
    } catch (error) {
      notify.update(
        'uploadVideo',
        tran.an_error_has_occured[config.lang],
        'error'
      );
      console.error(error);
    }
  };

  const deleteFile = async () => {
    try {
      arrayForCheck.value = [];
      arrayOfRequiredLabels.value = [];
      all_values.value = [];
      data.value = {};
      inference.reset();
      isUpload.value = false;
      isRetrain.value = false;
    } catch (err) {
      console.error(err);
    }
  };

  const retrainModel = async () => {
    try {
      // // if there are required labels show message
      // if (arrayOfRequiredLabels.value.length) {
      //   notify.error(tran.required_labels[config.lang]);
      //   return;
      // }
      // const formData = new FormData();
      // formData.append('model_id', model.active);
      // formData.append('data', JSON.stringify(data.value));
      // notify.loading('trainModel', tran['wait'][config.lang]);
      // // send request
      // await $larafetch('api/train/run', {
      //   formdata: true,
      //   method: 'POST',
      //   body: formData,
      // });
      // notify.update('trainModel', tran.modelTrained[config.lang], 'success');
      // // delete file
      // deleteFile();
    } catch (error) {
      // notify.update(
      //   'trainModel',
      //   tran.an_error_has_occured[config.lang],
      //   'error'
      // );
      console.error(error);
    }
  };

  const toResetLabels = (event) => {
    try {
      const element = event.target; // get element which to click
      const tr = element.closest('tr'); // find the 'tr' element

      const id = String(tr.id); // get id of 'li' element

      all_values.value[inference.currentSegment - 1].labels[id] = ''; // reset value of label
    } catch (err) {
      console.error(err);
    }
  };

  const addLabelsInArrayForCheck = (array) => {
    if (!array.length) return;

    array.forEach((element, index) => {
      arrayForCheck.value[index][inference.currentSegment - 1] = element;
    });

    toCheckLabels();
  };

  const toCheckLabels = () => {
    inference.labelsList.forEach((element, index) => {
      arrayOfRequiredLabels.value[index] = [];
      let arrayOfLabels = arrayForCheck.value[index];
      let label_0 = '';
      let label_1 = '';

      arrayOfLabels.forEach((el) => {
        if (label_0) return;
        label_0 = el === element[0];
      });

      arrayOfLabels.forEach((el) => {
        if (label_1) return;
        label_1 = el === element[1];
      });

      if (label_0 === false) {
        arrayOfRequiredLabels.value[index][0] = element[0];
      }

      if (label_1 === false) {
        arrayOfRequiredLabels.value[index][1] = element[1];
      }

      if (label_0 === true && label_1 === true)
        arrayOfRequiredLabels.value[index] = '';
    });

    arrayOfRequiredLabels.value = arrayOfRequiredLabels.value.filter(
      (element) => element !== ''
    );
  };

  const createData = (arr) => {
    let { labels } = arr[inference.currentSegment - 1];
    labels.forEach((element, idx) => {
      const id = inference.labelsList[idx].indexOf(element);
      const labels_id = inference.labelsList[idx][2];
      const hardLabel = id === -1 ? false : true;
      data.value[inference.videoId][labels_id].label_id = id;
      data.value[inference.videoId][labels_id].is_hard_label = hardLabel;
    });
  };

  const dropFile = (event) => {
    event.preventDefault();
    const files = event.dataTransfer.files;

    isDragging.value = false;

    if (!files.length) return;

    change({ file: files[0] });
  };

  const dragOver = () => (isDragging.value = true);

  const dragLeave = () => (isDragging.value = false);

  const toCreateChart = () => {
    chart = new ApexCharts(document.querySelector('#chart'), options.value);
  };

  const initSeriesForChart = () => {
    let { series } = options.value;
    // add required options for chart
    inference.labelsList.forEach((element) => {
      element.forEach((el) => {
        series.push({
          name: el,
          data: [
            {
              x: `${element[0]} / ${element[1]}`,
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
    inference.dataForCart.forEach((element, index) => {
      const { labels } = element;
      const { timestamp } = element;
      const step = 17000;
      const start = timestamp * 1000;
      let end = 0;

      if (inference.dataForCart.length - 1 === index) {
        end = inference.duration * 1000;
      } else {
        end = start + step;
      }

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
    () => all_values.value,
    (newValue) => {
      if (!newValue.length) return;

      // createData(newValue);

      // addLabelsInArrayForCheck(newValue[inference.currentSegment - 1].labels);
    },
    { deep: true }
  );

  onMounted(() => [
    window.Echo.channel('inference').listen('.new_log', (res) => {
      const video_id = Object.keys(res['log'])[0];
      const object_of_labels = Object.values(res['log'])[0];
      const labels = Object.values(object_of_labels);

      if (!video_id) return;
      inference.videoPaths.find((element) => {
        if (element.id == video_id) {
          element.inference = true;
        }
      });

      inference.resultLabels.push(labels);

      inference.resultLabels.forEach((element, index) => {
        all_values.value[index] = {
          timestamp: inference.startPoints[index],
          labels: [],
        };
        all_values.value[index].labels = element;
      });

      console.log(all_values.value);
    }),
  ]);
</script>

<style lang="scss" scoped>
  @import '@/public/sass/variables.scss';
  @import '~/public/sass/scrollbar.scss';

  .styled_scrollbar {
    @include scrollbars(5px);
  }
  .disabled {
    pointer-events: none;
    opacity: 0.7;
  }
  .container {
    max-width: 100% !important;
  }
  .wrapper {
    width: 100%;
    height: calc(100vh - 104.8px);
    margin: 0;
    padding: 40px;
    border-radius: 8px;
    background-color: $color-2;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.06),
      0px 1px 3px 0px rgba(16, 24, 40, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
    &__container {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 16px;
    }
    &__icon {
      width: 152px;
      height: 118px;
      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
    &__content {
      max-width: 352px;
      width: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    &__title {
      font-family: Inter;
      color: $color-34;
      font-size: 1rem;
      font-weight: 600;
      line-height: 1.5rem;
      text-align: center;
    }
    &__description {
      color: $color-16;
      text-align: center;
      font-family: Inter;
      font-size: 0.875rem;
      font-weight: 400;
      line-height: 1.25rem;
    }
    &__button {
      width: 100%;
      margin-top: 24px;
      .btn {
        width: 100%;
        padding: 10px 16px;
        background-color: $color-3;
        border: none;
        color: $color-2;
        &:hover {
          color: $color-2;
          background-color: $color-3;
        }
      }
    }
  }
  .main {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    row-gap: 16px;
  }
  .train {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    row-gap: 15px;
    &__container {
      display: flex;
      width: 100%;
      height: 100%;
      flex-direction: column;
      row-gap: 15px;
    }
    .toast {
      width: fit-content;
      min-width: fit-content;
    }
    .toast-header {
      color: red;
    }
  }
  .video-container {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    row-gap: 20px;
    &__block {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      width: 100%;
      height: 100%;
    }
    &__parts {
      display: flex;
      gap: 20px;
      flex-grow: 1;
      flex-wrap: wrap;
      .first-item,
      .third-item {
        height: 313px;
        flex: 1 0 auto;
      }
      .second-item {
        height: 313px;
        flex: 0 0 auto;
        display: flex;
        flex-direction: column;
        gap: 8px;
      }
    }
    &__list {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
  }
  .player {
    width: 486px;
    height: 100%;
    video {
      border-radius: 8px;
    }
  }
  .drop-zone {
    width: 445px;
    height: 242px;
    background-color: $color-2;
    padding: 10px 14px;
    border-radius: 8px;
    border: 2px dashed #d0d5dd;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    display: flex;
    justify-content: center;
    align-items: center;
    &__content {
      display: flex;
      gap: 16px;
      font-size: 1rem;
      font-weight: 600;
    }
    &__icon {
      width: 24px;
      height: 24px;
    }
    &__text {
      opacity: 0.5;
    }
    &--active {
      border: 2px dashed purple;
    }
    .drag-over {
      color: purple;
      stroke: purple;
      stroke-opacity: 1;
      opacity: 1;
    }
  }
  .btn {
    &--upload {
      font-size: 18px;
    }
    &__icon {
      display: inline-block;
      width: 20px;
      height: 20px;
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
  .table {
    overflow: auto;
    padding: 0;
    margin: 0;
  }
  .for-paths {
    padding: 0;
    background-color: $color-35;
  }
  .for-labels {
    padding: 0 0 10px 0;
    flex-direction: row;
    gap: 0;
  }
  .paths-table {
    max-height: calc(313px - 48px);
  }
  .labels-table {
    max-height: calc(313px - 63.09px);
  }
  .switch {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    &__counter {
      font-size: 0.875rem;
      font-weight: 500;
      line-height: 1.25rem;
      flex-shrink: 0;
    }
    &__btns {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      column-gap: 15px;
    }
  }
  table {
    min-width: 100%;
    border-collapse: collapse;
    border-spacing: 0px;
    border-radius: 8px;
    thead {
      border: none;
      th {
        color: $table-thead-color;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.75rem;
      }
    }
    tr {
      border: none;
    }
    td {
      color: $table-tr-th-color;
      font-size: 0.875rem;
      font-weight: 400;
      line-height: 1.25rem;
      padding: 16px 24px;
      border-left: none;
      border-right: none;
      &:nth-of-type(2) {
        border-right: none;
      }
      .btn {
        width: $icon-width !important;
        height: $icon-height !important;
        padding: 0 !important;
        border: none !important;
      }
    }
  }
  .labels {
    width: 100%;
    display: flex;
    flex-direction: column;
    &__header {
      display: flex;
      width: 100%;
      height: fit-content;
      justify-content: left;
      align-items: center;
      padding: 16px 24px;
      border-bottom: 1px solid $color-18;
      h5 {
        margin: 0;
        color: #000;
        font-family: Inter;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.25rem;
      }
    }
    &__icon {
      width: 20px;
      height: 20px;
    }
    label {
      min-width: 70px;
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      font-size: 0.875rem;
      font-weight: 500;
      line-height: 1.25rem;
    }
    table {
      td {
        color: $color-16;
        font-family: Inter;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.25rem;
      }
    }
    input[type='radio'] {
      display: none;
    }
  }
  .videos-list {
    &__header {
      display: flex;
      width: 100%;
      height: fit-content;
      justify-content: left;
      align-items: center;
      padding: 10px 10px 10px 24px;
      h5 {
        margin: 0;
        color: #000;
        font-family: Inter;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.75rem;
      }
    }
    &__body {
      ul,
      li {
        padding: 0;
        margin: 0;
        list-style: none;
      }
      ul {
        font-family: Inter;
        font-size: 0.875rem;
        color: $color-16;
        font-weight: 500;
        line-height: 1.25rem;
      }
      li {
        display: flex;
        gap: 12px;
        padding: 16px 24px;
        cursor: pointer;
      }
      .icon {
        width: $icon-width;
        height: $icon-height;
      }
    }
  }
  .clusters {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid $color-13;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.06),
      0px 1px 3px 0px rgba(16, 24, 40, 0.1);
    ul,
    li {
      padding: 0;
      margin: 0;
      list-style: none;
    }
    &__list {
      color: $color-15;
      font-size: 0.875rem;
      font-weight: 600;
      line-height: 1.25rem;
      display: flex;
      gap: 8px;
      .btn {
        padding: 10px 16px;
        border: 1px solid $color-29;
        background: $color-30;
      }
      .btn-active {
        background: $color-31;
      }
    }
  }
  .btn-train {
    color: $color-25;
    border: 1px solid $color-25;
    background-color: $color-2;
    &:hover {
      color: $btn-save-hover-color;
      background-color: $btn-save-hover-bg;
      border-color: $btn-save-hover-border;
    }
    &:active {
      color: $btn-save-active-color;
      background-color: $btn-save-active-bg;
      border-color: $btn-save-active-border;
    }
  }
  .icon-refresh:active {
    stroke: $btn-save-active-color;
  }
  .btn-delete {
    background-color: $color-22;
    border: none;
    &:hover {
      background-color: $color-22;
    }
  }
  .btn-train {
    padding: 10px 16px !important;
    background-color: $color-3 !important;
    border: none !important;
    color: $color-2 !important;
    &:hover {
      color: $color-2;
      background-color: $color-3;
    }
    &__icon {
      width: $icon-width;
      height: $icon-height;
      padding: 0;
      border: none;
    }
  }
  @media screen and (max-width: 1000px) {
    .train {
      width: 100%;
    }
  }
</style>
