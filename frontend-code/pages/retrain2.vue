<template>
  <NuxtLayout name="main">
    <Section title="Add new">
      <main>
        <Inference
          :isRetrain="isRetrain"
          @toRetrain="retrain"
        />
        <Retrain
          :isRetrain="isRetrain"
        />
      </main>
    </Section>

  </NuxtLayout>
</template>

<script setup>
// definePageMeta({middleware: ['auth']});

  let lang = ref(1);
  let isRetrain = ref(true);

  const retrain = newValue => {
    // update the value
    isRetrain.value = newValue.value;
  }
</script>

<style lang="scss" scoped>
  @import "~/public/css/reset.css";
  @import "~/public/sass/spinner.scss";

  main {
    display: grid;
    grid-template-columns: 100%;
    grid-template-rows: 100%;
    justify-items: center;
  }
</style>
