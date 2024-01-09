<template>
  <div class="container">
    <div class="title">
      <div class="title__text">
        <h1>Computer vision</h1>
      </div>
      <div
        v-if="false"
        id="select"
        :class="{
          title__select: true,
          disabled: isDisabled,
        }"
        @click="show = !show"
      >
        <div class="select">
          <div class="select__main">
            <div class="select__text">
              {{ modelName }}
            </div>
            <div class="select__icon">
              <IconsArrowDown class="icon-arrow" />
            </div>
          </div>
          <div
            v-if="show"
            class="select__drop-down"
          >
            <ul class="list styled_scrollbar">
              <li
                v-for="item in model.list"
                :key="item.id"
                class="list__items item"
                @click="configs.currentConfig = item.id"
              >
                <span class="item__text">
                  {{ item.name }}
                </span>
                <div
                  v-if="configs.currentConfig == item.id"
                  class="item__icon"
                >
                  <IconsCheck />
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import tran from '~/translates.json';

  const { id } = useRoute().params;
  const user = useUser();
  const config = useConfigStore();
  const model = useModelsStore();
  const configs = useConfigsStore();

  configs.currentConfig = id;

  await useAsyncData('models', async () => {
    const { data } = await $larafetchserver(`/api/models/${user.value.id}`);
    model.list = data;
  });

  if (model.list.length == 1) configs.currentConfig = 1;

  const props = defineProps({
    isDisabled: { type: Boolean, default: false },
  });

  let show = ref(false);

  let modelName = computed(() => {
    if (!configs.currentConfig) return tran.selectModel[config.lang];

    const element = model.list.find((item) => item.id == configs.currentConfig);

    return element.name;
  });

  onMounted(() => {
    window.addEventListener('click', (event) => {
      const parent = document.getElementById('select');
      const child = event.target;
      if (!parent.contains(child)) show.value = false;
    });
  });
</script>

<style lang="scss" scoped>
  @import '/public/sass/variables';
  @import '~/public/sass/scrollbar.scss';

  .styled_scrollbar {
    @include scrollbars(5px);
  }
  .disabled {
    pointer-events: none;
    opacity: 0.7;
  }
  ul,
  li {
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .container {
    margin: 0;
    padding: 0;
  }
  .title {
    width: 100%;
    height: 100%;
    background-color: $color-2;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.06),
      0px 1px 3px 0px rgba(16, 24, 40, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    h1 {
      color: #000;
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      padding: 0;
    }
    &__select {
      cursor: pointer;
    }
  }
  .select {
    position: relative;
    color: $color-16;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5rem;
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid $btn-border-color;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    &__main {
      width: 100%;
      height: 100%;
      display: flex;
      gap: 8px;
    }
    &__text {
      max-width: 196px;
      width: 196px;
    }
    &__icon {
      width: 20px;
      height: 20px;
    }
    &__drop-down {
      max-width: 196px;
      width: 196px;
      height: auto;
      border-radius: 8px;
      border: 1px solid $color-27;
      padding: 4px 0;
      background-color: $color-2;
      position: absolute;
      top: 50px;
      right: 0;
      z-index: 1;
      box-shadow: 0px 4px 6px -2px rgba(16, 24, 40, 0.03),
        0px 12px 16px -4px rgba(16, 24, 40, 0.08);
    }
  }
  .list {
    max-height: 220px;
    overflow: auto;
    background-color: $color-2;
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.5rem;
    &__items {
      background-color: inherit;
      width: 100%;
      height: 100%;
      padding: 10px 14px;
      &:hover {
        background-color: $color-7;
        cursor: pointer;
      }
    }
  }
  .item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    &__icon {
      width: $icon-width;
      height: $icon-height;
    }
  }
  .icon-arrow {
    stroke: $color-16;
  }
</style>
