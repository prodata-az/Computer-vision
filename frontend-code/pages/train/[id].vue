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
                          v-if="train.videoLoaded"
                          class="videos-list"
                        >
                          <div class="videos-list__header">
                            <h5>{{ tran.videos[config.lang] }}</h5>
                          </div>
                          <div class="table paths-table styled_scrollbar">
                            <div class="videos-list__body">
                              <ul class="">
                                <li
                                  v-for="(item, idx) in train.videoPaths"
                                  :key="idx"
                                  @click="train.currentSegment = idx + 1"
                                >
                                  <div class="icon">
                                    <IconsRadioActive
                                      v-if="train.currentSegment === idx + 1"
                                    />
                                    <IconsRadio v-else />
                                  </div>

                                  <div>
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
                            :src="train.src"
                          />
                        </div>
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
        <Popup />
      </ControlSection>
      <ControlSection
        v-if="isUpload"
        :footer="false"
      >
        <template v-slot:main>
          <div class="video-container__parts">
            <div class="first-item">
              <Container class="for-labels">
                <div
                  v-if="train.videoLoaded"
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
                          v-for="(row, index) in train.labelsList"
                          :id="index"
                          :key="index"
                        >
                          <td>
                            <div class="label-1">
                              <label>
                                <div class="labels__icon">
                                  <IconsChecklistActive
                                    v-if="
                                      all_values[train.currentSegment - 1]
                                        .labels[index] === row[0]
                                    "
                                  />
                                  <IconsChecklist v-else />
                                </div>
                                <input
                                  type="radio"
                                  :name="`labels_${index}`"
                                  :value="row[0]"
                                  v-model="
                                    all_values[train.currentSegment - 1].labels[
                                      index
                                    ]
                                  "
                                />
                                <span>
                                  {{ row[0] }}
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
                                      all_values[train.currentSegment - 1]
                                        .labels[index] === row[1]
                                    "
                                  />
                                  <IconsChecklist v-else />
                                </div>
                                <input
                                  type="radio"
                                  :name="`labels_${index}`"
                                  :value="row[1]"
                                  v-model="
                                    all_values[train.currentSegment - 1].labels[
                                      index
                                    ]
                                  "
                                />
                                <span>
                                  {{ row[1] }}
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
            <div class="second-item">
              <Container class="for-required">
                <div
                  v-if="true"
                  class="required-labels"
                >
                  <div class="required-labels__header">
                    <h5>Required labels</h5>
                  </div>
                  <div class="table required-table styled_scrollbar">
                    <table class="required-labels__main">
                      <tbody class="">
                        <tr
                          v-for="labels in arrayOfRequiredLabels"
                          class=""
                        >
                          <td>
                            <span>
                              {{ labels[0] }}
                            </span>
                            <span v-if="labels[0] && labels[1]">, </span>
                            <span>
                              {{ labels[1] }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <NoData
                  v-else
                  width="262px"
                />
              </Container>
            </div>
          </div>
        </template>
      </ControlSection>
      <ControlSection
        v-if="isUpload"
        style="gap: 10px"
      >
        <template v-slot:main>
          <div class="chart"></div>
        </template>
        <template v-slot:third-btn>
          <UXButton
            class="btn-train"
            @click="trainModel"
          >
            <span class="btn-train__icon">
              <IconsRefresh
                class="icon-refresh"
                stroke="#FFFFFF"
              />
            </span>
            <span>
              {{ tran.train[config.lang] }}
            </span>
          </UXButton>
        </template>
      </ControlSection>
    </div>
  </NuxtLayout>
</template>

<script setup>
  // definePageMeta({ middleware: ['auth'] });

  import tran from '~/translates.json';

  const config = useConfigStore();
  const notify = useNotifyStore();
  const configs = useConfigsStore();
  const train = useTrainStore();
  const { id } = useRoute().params;

  configs.currentConfig = id;

  let arrayForCheck = ref([]);
  let arrayOfRequiredLabels = ref([]);
  let all_values = ref([]);
  let data = ref({});
  let isDragging = ref(false);
  let isUpload = ref(false);

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

      train.file = event.file;
      await train.getVideoPaths();

      await train.getLabels();

      // add key for all_values
      train.startPoints.forEach((element, index) => {
        all_values.value[index] = { timestamp: element, labels: [] };
        train.labelsList.forEach((row, i) => {
          all_values.value[index].labels[i] = '';
        });
      });

      // create arrays for check
      train.labelsList.forEach((element, index) => {
        arrayForCheck.value[index] = [];
      });

      train.videosIds.forEach((element) => {
        data.value[element] = {};
        train.labelsList.forEach((el, idx) => {
          data.value[element][el[2]] = {
            label_id: -1,
            is_hard_label: false,
          };
        });
      });

      train.videoLoaded = true;

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
      train.reset();
      isUpload.value = false;
    } catch (err) {
      console.error(err);
    }
  };

  const trainModel = async () => {
    try {
      // if there are required labels show message
      if (arrayOfRequiredLabels.value.length) {
        notify.error(tran.required_labels[config.lang]);
        return;
      }

      const formData = new FormData();
      formData.append('model_id', configs.currentConfig);
      formData.append('data', JSON.stringify(data.value));

      notify.loading('trainModel', tran['wait'][config.lang]);

      $larafetch('api/train/run', {
        formdata: true,
        method: 'POST',
        body: formData,
      });

      notify.update('trainModel', tran.modelTrained[config.lang], 'success');

      setTimeout(() => {
        window.location = '/models';
      }, 2000);
    } catch (error) {
      notify.update(
        'trainModel',
        tran.an_error_has_occured[config.lang],
        'error'
      );
      console.error(error);
    }
  };

  const toResetLabels = (event) => {
    try {
      const element = event.target; // get element which to click
      const tr = element.closest('tr'); // find the 'tr' element

      const id = String(tr.id); // get id of 'li' element

      all_values.value[train.currentSegment - 1].labels[id] = ''; // reset value of label
    } catch (err) {
      console.error(err);
    }
  };

  const addLabelsInArrayForCheck = (array) => {
    if (!array.length) return;

    array.forEach((element, index) => {
      arrayForCheck.value[index][train.currentSegment - 1] = element;
    });

    toCheckLabels();
  };

  const toCheckLabels = () => {
    train.labelsList.forEach((element, index) => {
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

    // let counts = [];
    // let str_list1 = train.labelsList.flat().filter((item) => typeof item === 'string');

    // let str_list2 = all_values.value.flatMap((d) => d.labels);

    // str_list2.forEach( (item, idx) => {

    //   if (str_list1.includes(item)) {
    //     counts[item] = counts[item] ? counts[item] + 1 : 1;
    //   }
    // });

    // console.log(counts);
  };

  const createData = (arr) => {
    let { labels } = arr[train.currentSegment - 1];
    labels.forEach((element, idx) => {
      const id = train.labelsList[idx].indexOf(element);
      const labels_id = train.labelsList[idx][2];
      const hardLabel = id === -1 ? false : true;
      data.value[train.videoId][labels_id].label_id = id;
      data.value[train.videoId][labels_id].is_hard_label = hardLabel;
    });
  };

  const dropFile = (event) => {
    if (!configs.currentConfig) {
      isDragging.value = false;
      return notify.info(tran.pıeaseSelectModel[config.lang]);
    }

    event.preventDefault();
    const files = event.dataTransfer.files;

    isDragging.value = false;

    if (!files.length) return;

    if (files.length > 1) return notify.info('Only one video!');

    change({ file: files[0] });
  };

  const dragOver = () => (isDragging.value = true);

  const dragLeave = () => (isDragging.value = false);

  watch(
    () => all_values.value,
    (newValue) => {
      if (!newValue.length) return;

      createData(newValue);

      addLabelsInArrayForCheck(newValue[train.currentSegment - 1].labels);
    },
    { deep: true }
  );
</script>

<style lang="scss" scoped>
  @import '@/public/sass/variables.scss';
  @import '~/public/sass/scrollbar.scss';

  .styled_scrollbar {
    @include scrollbars(5px);
  }
  .container {
    max-width: 100% !important;
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
      .first-item {
        height: 313px;
        flex: 1 0 auto;
      }
      .second-item {
        height: 313px;
        flex: 0 0 auto;
      }
    }
    &__list {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
  }
  .player {
    width: 466px;
    height: 100%;
    video {
      border-radius: 8px;
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
  .table {
    overflow: auto;
    padding: 0;
    margin: 0;
  }
  .paths-table {
    max-height: calc(313px - 48px);
  }
  .labels-table {
    max-height: calc(313px - 63.09px);
  }
  .required-table {
    max-height: calc(313px - 63.09px);
  }
  table {
    min-width: 100%;
    border-collapse: collapse;
    border-spacing: 0px;
    border-radius: 8px;
    thead {
      color: $table-thead-color;
      font-size: 0.875rem;
      font-weight: 600;
      line-height: 1.75rem;
      border: none;
    }
    th,
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
    tr {
      border-bottom: 1px solid $table-border-color;
    }
    tbody tr:nth-child(odd) {
      background-color: #f9fafb;
    }
  }
  .for-paths {
    padding: 0;
    background-color: $color-35;
  }
  .for-labels {
    padding: 0 0 10px 0;
    background-color: $color-35;
    flex-direction: row;
    gap: 0;
  }
  .for-required {
    padding: 0 0 10px 0;
    flex-direction: row;
    gap: 0;
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
  .required-labels {
    &__header {
      display: flex;
      width: 262px;
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
    table {
      tbody tr:nth-child(odd) {
        background-color: $color-2;
      }
      td {
        color: $color-16;
        font-family: Inter;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.25rem;
      }
      tr {
        border-bottom: none;
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
  main {
    width: 100%;
    height: 100%;
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
  .chart {
    height: 332px;
  }
  @media screen and (max-width: 1000px) {
    .train {
      width: 100%;
    }
  }
</style>
