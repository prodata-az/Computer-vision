<template>
  <div
    class="popup modal show"
    :style="`display: ${show ? 'block' : 'none'}`"
  >
    <div class="modal-dialog modal-dialog-centered"
      :style="`max-width: ${modalWidth}px`"
    >
      <form class="popup__container modal-content">
        <div class="popup__header">
          <h4>{{ title }}</h4>
        </div>
        <div class="popup__main">
          <slot></slot>
        </div>
        <div class="popup__footer">
          <div class="popup__btns">
            <UXButton @click="toCancel">
              {{ tran.close[config.lang] }}
            </UXButton>
            <UXButtonSave
              type="submit"
              :disabled="!value"
              @click.prevent="toSaveChange"
            />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
  import tran from '~/translates.json';

  const config = useConfigStore();

  const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Title' },
    value: { type: String, default: '' },
    modalWidth: { type: String, default: '486' },
  });

  const emit = defineEmits(['cancel', 'save']);

  const toSaveChange = async () => {
    emit('save');
  };

  const toCancel = () => {
    emit('cancel');
  };
</script>

<style lang="scss" scoped>
  @import '@/public/sass/variables.scss';

  .popup {
    &__container {
      margin: auto;
      background-color: $color-2;
      border-radius: 8px;
      border: 1px solid $color-18;
      box-shadow: 0px 4px 6px -2px rgba(16, 24, 40, 0.03),
        0px 12px 16px -4px rgba(16, 24, 40, 0.08);
    }
    &__header {
      display: flex;
      justify-content: space-between;
      padding: 20px 24px;
      color: $color-34;
      font-family: Inter;
      font-size: 1.125rem;
      font-weight: 500;
      line-height: 1.75rem;
      border-bottom: 1px solid $color-18;
      h4 {
        margin: 0;
        padding: 0;
      }
    }
    &__main {
      padding: 20px;
    }
    &__footer {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: end;
      padding: 16px 20px;
      border-top: 1px solid $color-18;
    }
    &__btns {
      display: flex;
      gap: 12px;
      justify-content: center;
      align-items: center;
    }
  }
</style>
