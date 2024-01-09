<template>
  <div class="container">
    <div class="title">
      <div class="title__text">
        <div v-if="backButton" class="icon-back">
          <IconsArrowLeft />
        </div>
        <h1>{{ modelName }}</h1>
      </div>
      <div>
        <slot name="button"/>
      </div>
    </div>
  </div>
</template>

<script setup>
  const { id } = useRoute().params;
  const user = useUser();
  const model = useModelsStore();
  const configs = useConfigsStore();

  configs.currentConfig = id;

  await useAsyncData('models', async () => {
    const { data } = await $larafetchserver(`/api/models/${user.value.id}`);
    model.list = data;
  });

  let modelName = computed(() => {
    if (!configs.currentConfig) return 'Computer vision';

    const element = model.list.find((item) => item.id == configs.currentConfig);

    return element.name;
  });

  const props = defineProps({
    backButton: { type: Boolean, default: true }
  });
</script>

<style lang="scss" scoped>
  @import '/public/sass/variables';
  @import '~/public/sass/scrollbar.scss';

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
    &__text {
      display: flex;
      gap: 15px;
      justify-content: center;
      align-items: center;
    }
    h1 {
      color: #000;
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      padding: 0;
    }
  }
  .icon-back {
    width: 24px;
    height: 24px;
  }
</style>
