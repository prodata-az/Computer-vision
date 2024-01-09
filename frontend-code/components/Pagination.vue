<template>
  <div
    v-if="lastPage > 1"
    class="pagination"
  >
    <div class="pagination__container">
      <div class="pagination__btn">
        <UXButton
          id="previous"
          class="btn"
          @click="changeCurrentPage"
          :disabled="isFirstPage"
        >
          <div class="btn__icon">
            <IconsArrowLeft />
          </div>
          <span>{{ tran['previous'][config.lang] }}</span>
        </UXButton>
      </div>
      <vue-awesome-paginate
        v-model="currentPage"
        :total-items="totalItems"
        :items-per-page="perPage"
        :max-pages-shown="lastPage"
        :hide-prev-next="true"
        :on-click="onClickHandler"
        paginate-buttons-class="paginate-buttons"
        active-page-class="active-page"
      />
      <div class="pagination__btn">
        <UXButton
          id="next"
          class="btn"
          @click="changeCurrentPage"
          :disabled="isLastPage"
        >
          <span>{{ tran['next'][config.lang] }}</span>
          <div class="btn__icon">
            <IconsArrowRight />
          </div>
        </UXButton>
      </div>
    </div>
  </div>
</template>

<script setup>
  import tran from '~/translates.json';

  const config = useConfigStore();

  let currentPage = ref(1);

  const props = defineProps({
    page: { type: Number, required: true },
    totalItems: { type: Number, required: true },
    perPage: { type: Number, required: true },
    lastPage: { type: Number, required: true },
  });

  const emit = defineEmits(['currentPageChanged']);

  let isFirstPage = computed(() => (currentPage.value === 1 ? true : false));
  let isLastPage = computed(() =>
    currentPage.value === props.lastPage ? true : false
  );

  const onClickHandler = (page) => {
    currentPage.value = page;
  };

  const changeCurrentPage = (element) => {
    if (element.target.closest('#previous')) currentPage.value--;
    if (element.target.closest('#next')) currentPage.value++;
  };

  watch(
    () => currentPage.value,
    (newValue) => {
      emit('currentPageChanged', newValue);
    },
    { deep: true }
  );

  watch(
    () => props.page,
    (newValue) => {
      currentPage.value = newValue;
    },
    { deep: true }
  );
</script>

<style lang="scss">
  @import '@/public/sass/variables.scss';

  .pagination {
    padding: 12px 24px 16px 24px !important;
    border-top: 1px solid $table-border-color;
    &__container {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
    }
    ul,
    li {
      list-style: none;
      padding: 0;
      margin: 0;
    }
  }

  .pagination-container {
    display: flex;
    column-gap: 2px;
  }
  .paginate-buttons {
    min-width: 40px;
    height: 40px;
    padding: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    background-color: $page-bg;
    color: $page-color;
  }
  .active-page {
    color: $page-current-color;
    background-color: $page-current-bg;
  }
  .btn {
    &__icon {
      width: $icon-width;
      height: $icon-height;
    }
  }
</style>
