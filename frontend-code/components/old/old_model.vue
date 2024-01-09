<template>
<NuxtLayout name="main">
  <section class="configs">
    <div class="configs__container">
      <Section :title="tran['add_new'][config.lang]">
        <form class="configs__add">
          <div class="inputs">
            <div class="inputs__elment">
              <UXInput
                id="addConfig"
                class="form-control"
                label="name"
                placeholder="My configs"
                v-model="configs.name"
              />
            </div>
          </div>
          <div class="btns">
            <div class="btns__add">
              <UXButton
                type="submit"
                @click.prevent="toAddConfig"
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
          <table v-if="configs.list.length" class="configTable">
            <colgroup>
              <col width="10px" span="1">
              <col span="1">
              <col width="10px" span="3">
            </colgroup>
            <thead>
              <tr>
                <th></th>
                <th>{{ tran.name[config.lang] }}</th>
                <th>{{ tran.delete[config.lang] }}</th>
                <th>{{ tran.edit[config.lang] }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in configs.list" :key="index" class="tableOfLabels__row">
                <td>{{ index + 1 }}</td>
                <td class="tableOfLabels__column name">
                  <NuxtLink
                    :to="`/configs/${row.id}`"
                  >
                    {{ row.name }}
                  </NuxtLink>
                </td>
                <td class="tableOfLabels__column">
                  <UXButton
                    @click="() => toShowPopup(row.id)"
                  >
                    <IconsDelete />
                  </UXButton>
                </td>
                <td class="tableOfLabels__column">
                  <UXButton
                    @click="toRename(row.id)"
                  >
                    <IconsEdit />
                  </UXButton>
                </td>
              </tr>
            </tbody>
          </table>
          <NoData v-else height="300px"/>
          <Pagination
            :page="configs.currentPage"
            :totalItems="configs.totalItems"
            :perPage="configs.perPage"
            :lastPage="configs.lastPage"
            @currentPageChanged="toUpdate"
          />
        </div>
      </Section>
      <DeleteConfirmPopup
        :show="show_delete_confirm_popup"
        @close="() => show_delete_confirm_popup = false"
        @delete="toDeleteModel"
      />
      <Modal
        :title="tran['rename'][config.lang]"
        :show="configs.showRename"
        :value="configs.newName"
        @cancel="toCancel"
        @save="toSave"
      >
        <div class="input-block">
          <UXInput
            id="rename"
            class="form-control"
            label="name"
            v-model="configs.newName"
            placeholder="My configs"
          />
        </div>
      </Modal>
    </div>
  </section>
</NuxtLayout>
</template>

<script setup>
  // definePageMeta({middleware: ['auth']});

  import tran from '~/translates.json';

  const config = useConfigStore();
  const model = useModelsStore();
  const configs = useConfigsStore();
  const { id } = useRoute().params;

  let show_delete_confirm_popup = ref(false);

  const toShowPopup = id => {
    configs.idConfig = id;

    // show popup
    show_delete_confirm_popup.value = true;
  };

  const toAddConfig = () => {
    configs.clearErrors();
    configs.add();
  }

  const toDeleteModel = () => {
    // close popup
    show_delete_confirm_popup.value = false;

    configs.del();
  };

  const toRename = id => {
    configs.clearErrors();
    configs.idConfig = id;
    configs.getName();
    configs.showRename = true;
  };

  const toSave = () => {
    configs.clearErrors();
    configs.rename();
  }

  const toUpdate = newValue => {
    configs.currentPage = newValue;
  }

  const toCancel = () => {
    // close modal
    configs.showRename = false;

    configs.cancel();
  }

  onBeforeMount(async() => {
    // await model.getActive();
    model.currentModel = id;
    configs.getList();
  })

  onMounted(() => {
    const element = document.getElementById('addConfig');
    configs.input = element;
  });
</script>

<style lang="scss">
  @import "~/public/css/reset.css";
  @import "@/public/sass/variables.scss";
  @import "../node_modules/@vueform/toggle/themes/default.scss";

  .configs {
    &__container {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      row-gap: 24px;
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
    }
    .inputs {
      width: 100%;
      height: 100%;
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      width: fit-content;
      flex: 0 0 fit-content;
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
                0px 1px 3px 0px rgba(16, 24, 40, 0.10);
  }

  table  {
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
    th, td {
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

  .configTable {
    tr {
      height: 72px;
    }
    tbody tr:nth-child(odd) {
      background-color: #F9FAFB;
    }
    th, td {
      padding: 12px 24px;
      border-right: none;
    }
    td:not(.name) {
      text-align: center;
    }
  }
</style>