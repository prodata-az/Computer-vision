<template>
  <div class="relative_100">
    <label :for="id" class="m_label">
      {{ label }}
    </label>
  </div>
  <input
    :id="id"
    :type="type"
    :value="modelValue"
    :class="class"
    :placeholder="placeholder"
    :disabled="disabled"
    :autocomplete="autocomplete"
    @input="$emit('update:modelValue', $event.target.value)"
  >
</template>

<script setup>
  import tran from '~/translates.json';

  const config = useConfigStore();

  const props = defineProps({
    id: { type: String, default: '', required: true},
    type: { type: String, default: 'text' },
    label: { type: String, default: 'Label' },
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: 'Placeholder' },
    disabled: { type: Boolean, default: false },
    class: { type: String, default: '' },
    autocomplete: { type: String, default: 'off' },
  });

  const emit = defineEmits([
    'update:modelValue'
  ]);
</script>

<style lang="scss" scoped>
@import "@/public/sass/variables.scss";

.is-invalid {
  border-color: #dc3545 !important;
  box-shadow: 0 0 0 0.25rem rgba(220,53,69,.25) !important;
}

label {
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.25rem;
  color: $input-label-color;
}
  input {
    border-radius: 8px;
    border: 1px solid $input-border-color;
    background: $input-bg;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    font-size: 1rem;
    line-height: 1.5rem;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    &::placeholder {
      color: $placeholder-color;
      font-size: 1rem;
      line-height: 1.5rem;
    }
  }
</style>
