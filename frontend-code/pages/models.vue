<template>
  <NuxtLayout name="main">
    <section class="models">
      <div class="models__container">
        <TitleComp :back-button="false" />
        <Section :title="tran.add_new_model[config.lang]">
          <form class="models__add">
            <div class="inputs">
              <div class="inputs__elment">
                <UXInput
                  id="addModel"
                  class="form-control"
                  :label="tran.name[config.lang]"
                  :placeholder="tran['myModels'][config.lang]"
                  v-model="model.name"
                />
              </div>
            </div>
            <div class="btns">
              <div class="btns__add">
                <UXButton
                  type="submit"
                  @click.prevent="toAddModel"
                >
                  <div class="icon">
                    <IconsAdd />
                  </div>
                  {{ tran['add'][config.lang] }}
                </UXButton>
              </div>
            </div>
          </form>
        </Section>
        <Section :title="tran['list'][config.lang]">
          <div class="table-container">
            <table
              v-if="model.list.length"
              class="modelTable"
            >
              <colgroup>
                <col
                  width="10px"
                  span="1"
                />
                <col span="1" />
                <col
                  width="10px"
                  span="3"
                />
              </colgroup>
              <thead>
                <tr>
                  <th></th>
                  <th>{{ tran.name[config.lang] }}</th>
                  <th>{{ tran.action[config.lang] }}</th>
                  <th>{{ tran.delete[config.lang] }}</th>
                  <th>{{ tran.edit[config.lang] }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(row, index) in model.list"
                  :key="index"
                  class="tableOfLabels__row"
                >
                  <td>{{ index + 1 }}</td>
                  <td class="tableOfLabels__column name">
                    {{ row.name }}
                  </td>
                  <td class="tableOfLabels__column">
                    <a
                      v-if="row.inference == 0"
                      :href="`/configs/${row.id}`"
                    >
                      <UXButton>
                        {{ tran.open[config.lang] }}
                      </UXButton>
                    </a>
                    <UXButton
                      v-else-if="row.inference == 1"
                      :disabled="true"
                    >
                      {{ tran.training[config.lang] }}
                    </UXButton>
                    <a
                      v-else-if="row.inference == 2"
                      :href="`/inference/${row.id}`"
                    >
                      <UXButton>
                        {{ tran.inference[config.lang] }}
                      </UXButton>
                    </a>
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
              :page="model.currentPage"
              :totalItems="model.totalItems"
              :perPage="model.perPage"
              :lastPage="model.lastPage"
              @currentPageChanged="toUpdate"
            />
          </div>
        </Section>
        <DeleteConfirmPopup
          :show="show_delete_confirm_popup"
          @close="() => (show_delete_confirm_popup = false)"
          @delete="toDeleteModel"
        />
        <Modal
          :title="tran['edit'][config.lang]"
          :show="model.showRename"
          :value="model.newName"
          @cancel="toCancel"
          @save="toSave"
        >
          <div class="input-block">
            <UXInput
              id="rename"
              class="form-control"
              :label="tran.name_of_model[config.lang]"
              :placeholder="tran.myModels[config.lang]"
              v-model="model.newName"
            />
          </div>
        </Modal>
      </div>
    </section>
  </NuxtLayout>
</template>

<script setup>
  // definePageMeta({ middleware: ['guest'] });

  import tran from '~/translates.json';
  const user = useUser();
  const config = useConfigStore();
  const model = useModelsStore();

  let show_delete_confirm_popup = ref(false);
  let isTraining = ref(false);

  const { data: all } = await useAsyncData('all', () =>
    $larafetchserver(`/api/models?user_id=${user.value.id}&page=${model.currentPage}`)
  );

  model.lastPage = all.value.meta.last_page;
  model.totalItems = all.value.meta.total;
  model.perPage = all.value.meta.per_page;
  model.list = all.value.data;

  const toShowPopup = (id) => {
    model.idModel = id;
    show_delete_confirm_popup.value = true;
  };

  const toAddModel = () => {
    model.clearErrors();
    model.add();
  };

  const toDeleteModel = () => {
    show_delete_confirm_popup.value = false;
    model.del();
  };

  const toRename = (id) => {
    model.clearErrors();
    model.idModel = id;
    model.getName();
    model.showRename = true;
  };

  const toSave = () => {
    model.clearErrors();
    model.rename();
  };

  const toUpdate = (newValue) => {
    model.currentPage = newValue;
  };

  const toCancel = () => {
    model.showRename = false;
    model.cancel();
  };

  onMounted(async () => {
    const element = document.getElementById('addModel');
    model.input = element;
  });
</script>

<style lang="scss" scoped>
  @import '~/public/css/reset.css';
  @import '@/public/sass/variables.scss';
  @import '../node_modules/@vueform/toggle/themes/default.scss';

  .container {
    max-width: 100% !important;
  }

  .models {
    &__container {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      row-gap: 16px;
    }
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    row-gap: 24px;
    &__add {
      width: 100%;
      height: 100%;
      display: flex;
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
        color: $color-33;
        font-family: Inter;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.125rem;
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
      .btn {
        color: $color-15;
        font-family: Inter;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.25rem;
        padding: 8px 14px;
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
  .modelTable {
    thead tr th:nth-of-type(3) {
      text-align: center;
    }
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
    td:not(.name) {
      text-align: center;
    }
    .toggle-container:focus {
      box-shadow: none !important;
    }

    .toggle {
      background-color: #ffffff;
      border: 3px solid #000000;
    }
    .handle {
      position: absolute;
      background: black;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      top: unset;
      left: 2px;
      transition-property: all;
      transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
      transition-duration: 150ms;
    }
    .toggle-on {
      background-color: #10b981;
      border: 3px solid #10b981;
    }
    .toggle-handle-on {
      background-color: #ffffff;
      left: 45px;
    }
  }
</style>
