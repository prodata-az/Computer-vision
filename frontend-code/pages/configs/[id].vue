<template>
  <NuxtLayout name="main">
    <section class="make_labels">
      <div class="make_labels__container">
        <div class="label">
          <TitleComp>
            <template v-slot:button>
              <a :href="`/train/${id}`">
                <UXButton class="btn-train">
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
              </a>
            </template>
          </TitleComp>
          <Section :title="tran.add_new_label[config.lang]">
            <form class="label__add">
              <div class="inputs">
                <div class="inputs__elment">
                  <UXInput
                    id="add_label_0"
                    class="form-control"
                    :label="`${tran.label[config.lang]} 1`"
                    :placeholder="`${tran['example'][config.lang]}: 'Drink'`"
                    v-model="labels.label_0"
                  />
                </div>
                <div class="inputs__elment">
                  <UXInput
                    id="add_label_1"
                    class="form-control"
                    :label="`${tran.label[config.lang]} 2`"
                    v-model="labels.label_1"
                    :placeholder="`${
                      tran['example'][config.lang]
                    }: 'Not drink'`"
                  />
                </div>
              </div>
              <div class="btns">
                <div class="btn-add">
                  <UXButton
                    type="submit"
                    @click.prevent="toAddLabels"
                  >
                    <div class="icon">
                      <IconsAdd />
                    </div>
                    {{ tran['add'][config.lang] }}
                  </UXButton>
                </div>
                <div
                  v-if="false"
                  class="btn-send"
                >
                  <UXButton
                    type="submit"
                    :disabled="!labels.list"
                  >
                    <div class="icon">
                      <IconsSend />
                    </div>
                    {{ tran['send'][config.lang] }}
                  </UXButton>
                </div>
              </div>
            </form>
          </Section>
          <Section :title="tran.rangeForLevels[config.lang]">
            <div
              class="levels-container"
              @mouseup="updateConfidenceScore"
            >
              <div
                class="table-container"
                style="box-shadow: none; flex: 0 0 245px;"
              >
                <table class="tableOfRanges">
                  <thead>
                    <tr>
                      <th>{{ tran['level'][config.lang] }}</th>
                      <th>{{ tran['value'][config.lang] }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ tran['low'][config.lang] }}</td>
                      <td>{{ `0% - ${value[1] - 1}%` }}</td>
                    </tr>
                    <tr>
                      <td>{{ tran['medium'][config.lang] }}</td>
                      <td>{{ `${value[1]}% - ${value[2]}%` }}</td>
                    </tr>
                    <tr>
                      <td>{{ tran['high'][config.lang] }}</td>
                      <td>{{ `${Number(value[2]) + 1}% - 100%` }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="slider-container">
                <div class="slider-container__main">
                  <Slider
                    id="slider"
                    v-model="value"
                    :min="min"
                    :max="max"
                    :step="step"
                    :tooltips="tooltips"
                    :showTooltip="showTooltip"
                    :lazy="lazy"
                    :options="options"
                  />
                </div>
              </div>
            </div>
          </Section>
          <Section :title="tran.list[config.lang]">
            <div class="table-container">
              <table
                v-if="labels.list.length"
                class="tableOfLabels"
              >
                <colgroup>
                  <col
                    width="10px"
                    span="1"
                  />
                  <col span="2" />
                  <col
                    width="10px"
                    span="2"
                  />
                </colgroup>
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ `${tran.label[config.lang]} 1` }}</th>
                    <th>{{ `${tran.label[config.lang]} 2` }}</th>
                    <th>{{ tran.delete[config.lang] }}</th>
                    <th>{{ tran.edit[config.lang] }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(row, index) in labels.list"
                    :key="index"
                    class="tableOfLabels__row"
                  >
                    <td>{{ index + 1 }}</td>
                    <td class="tableOfLabels__column label-1">
                      {{ row.label_0 }}
                    </td>
                    <td class="tableOfLabels__column label-2">
                      {{ row.label_1 }}
                    </td>
                    <td class="tableOfLabels__column">
                      <UXButton
                        class="btn-icon"
                        @click="() => toShowPopup(row.id)"
                      >
                        <IconsDelete />
                      </UXButton>
                    </td>
                    <td class="tableOfLabels__column">
                      <UXButton
                        class="btn-icon"
                        @click="toRename(row.id)"
                      >
                        <IconsEdit />
                      </UXButton>
                    </td>
                  </tr>
                </tbody>
              </table>
              <NoData
                v-else
                height="300px"
              />
              <Pagination
                :page="labels.currentPage"
                :totalItems="labels.totalItems"
                :perPage="labels.perPage"
                :lastPage="labels.lastPage"
                @currentPageChanged="toUpdate"
              />
            </div>
          </Section>
          <DeleteConfirmPopup
            :show="show_delete_confirm_popup"
            @close="() => (show_delete_confirm_popup = false)"
            @delete="toDeleteLabels"
          />
          <Modal
            :title="tran['edit'][config.lang]"
            :show="labels.showRename"
            :value="labels.newNameLabel_0"
            modal-width="750"
            @cancel="toCancel"
            @save="toSave"
          >
            <div class="inputs-container">
              <div class="input-block">
                <UXInput
                  id="rename_label_0"
                  class="form-control"
                  :label="`${tran.label[config.lang]} 1`"
                  :placeholder="`${tran['example'][config.lang]}: 'Drink'`"
                  v-model="labels.newNameLabel_0"
                />
              </div>
  
              <div class="input-block">
                <UXInput
                  id="rename_label_1"
                  class="form-control"
                  :label="`${tran.label[config.lang]} 2`"
                  v-model="labels.newNameLabel_1"
                  :placeholder="`${tran['example'][config.lang]}: 'Not drink'`"
                />
              </div>
            </div>
          </Modal>
        </div>
      </div>
    </section>
  </NuxtLayout>
</template>

<script setup>
  // definePageMeta({ middleware: ['auth'] });

  import tran from '~/translates.json';
  import Slider from '@vueform/slider';

  const labels = useLabelsStore();
  const config = useConfigStore();
  const configs = useConfigsStore();
  const { id } = useRoute().params;

  let show_delete_confirm_popup = ref(false);

  await useAsyncData('label', async () => {
    const response = await $larafetchserver(
      `/api/labels?config_id=${id}&page=${labels.currentPage}`
    );

    labels.lastPage = response.last_page;
    labels.totalItems = response.total;
    labels.perPage = response.per_page;
    labels.list = response.data;
  });

  await useAsyncData('score', async () => {
    const response = await $larafetchserver(`/api/configs/${id}/confidence_score`);

    configs.confidenceScore = [];
    configs.confidenceScore.push(response['medium_min']);
    configs.confidenceScore.push(response['medium_max']);
  });

  // ***** All variables for levels *****
  let min = 0;
  let max = 100;
  let step = 1;
  let tooltips = true;
  let showTooltip = 'always';
  let lazy = false;
  let options = {
    connect: [false, false, true, false, false],
    behaviour: 'drag',
    margin: 10,
  };

  let value = ref([
    0,
    configs.confidenceScore[0],
    configs.confidenceScore[1],
    100,
  ]);
  // ***************************************

  const toShowPopup = (id) => {
    labels.idLabels = id;
    show_delete_confirm_popup.value = true;
  };

  const toAddLabels = async () => {
    labels.clearErrors();
    labels.add();
  };

  const toUpdate = (newValue) => {
    labels.currentPage = newValue;
  };

  const toDeleteLabels = () => {
    show_delete_confirm_popup.value = false;
    labels.del();
  };

  const toRename = (id) => {
    labels.clearErrors();
    labels.idLabels = id;
    labels.getName();
    labels.showRename = true;
  };

  const toSave = () => {
    labels.clearErrors();
    labels.rename();
  };

  const toCancel = () => {
    labels.showRename = false;
    labels.cancel();
  };

  const updateConfidenceScore = () => {
    const min = value.value[1];
    const max = value.value[2];

    configs.setConfidenceScore(min, max);
  };
</script>

<style lang="scss">
  @import '@/public/sass/variables.scss';
  @import '@vueform/slider/themes/default.scss';

  .container {
    max-width: 100% !important;
  }
  .is-valid {
    border-color: #198754 !important;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25) !important;
  }
  .is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
  }
  .make_labels {
    width: 100%;
  }
  .label {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    row-gap: 16px;
    &__add {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      .btn {
        line-height: 1.5rem;
      }
      .icon {
        display: flex;
        justify-content: center;
        align-items: center;
      }
    }
    &__list {
      width: 300px;
      display: flex;
      background: #3290ff47;
      border-radius: 35px;
      flex-direction: column;
      row-gap: 15px;
      padding: 15px 30px;
    }
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
    .inputs {
      width: 100%;
      height: 100%;
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      width: fit-content;
      flex: 1 0 fit-content;
      &__elment {
        flex: 1 0 auto;
      }
    }
  }
  .btns {
    display: flex;
    gap: 16px;
    align-items: end;
    .icon {
      width: $icon-width;
      height: $icon-height;
    }
  }
  .levels-container {
    display: flex;
    flex-wrap: wrap;
    column-gap: 10px;
  }
  .level-table {
    height: fit-content;
    .m_scroll {
      margin: 0;
    }
  }
  .slider-container {
    border-radius: 8px;
    border: 1px solid $color-36;
    background: $color-35;
    row-gap: 40px;
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    &__main {
      max-width: 492px;
      width: 100%;
    }
    .slider-tooltip {
      background-color: $color-37;
      border: 1px solid $color-37;
      border-radius: 8px;
      padding: 8px;
      font-size: 0.875rem;
      font-weight: 600;
      line-height: 20px;
    }
    .slider-connect {
      background-color: $slider-tooltip-bg;
    }
    .slider-handle {
      &:focus {
        box-shadow: 0 0 0 3px $slider-handle-bg-focus;
      }
    }
  }
  .slider-base {
    background-color: $slider-base-bg;
    .slider-origin:nth-child(2),
    .slider-origin:nth-child(5) {
      .slider-handle {
        touch-action: none;
        pointer-events: none;
        touch-action: pan-x pan-y;
        &:focus {
          box-shadow: $slider-handle-bg-focus;
        }
      }
    }
  }
  .table-container {
    border: 1px solid $table-border-color;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.06),
      0px 1px 3px 0px rgba(16, 24, 40, 0.1);
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
        padding: 10px 10px 10px 24px;
      }
    }
    td {
      color: $table-tr-th-color;
      font-size: 0.875rem;
      font-weight: 400;
      line-height: 1.25rem;
      padding: 10px 10px 10px 24px;
      border-left: none;
      border-right: 1px solid $table-border-color;
      &:nth-of-type(2) {
        border-right: none;
      }
      .btn-icon {
        width: $icon-width !important;
        height: $icon-height !important;
        padding: 0 !important;
        border: none !important;
      }
    }
    tr {
      border-bottom: 1px solid $table-border-color;
    }
    tbody tr:nth-last-child(1) {
      border-bottom: none;
    }
  }
  .tableOfRanges {
    background: $color-35;
    tr,
    td {
      border: none;
    }
  }
  .tableOfLabels {
    tr {
      height: 72px;
    }
    tbody tr:nth-child(odd) {
      background-color: #f9fafb;
    }
    th,
    td {
      padding: 12px 24px;
      border-right: none;
    }
    td:not(.label-1, .label-2) {
      text-align: center;
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
  .inputs-container {
    display: flex;
    gap: 16px;
  }
  .input-block {
    flex-grow: 1;
  }
</style>
