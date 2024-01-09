<template>
  <NuxtLayout name="main">
    <main>
      <MakeLabel
        :isSend="isSend"
        @labels-sended="change"
      />
      <Train
        :labelSended="isSend"
      />
    </main>

  </NuxtLayout>
</template>

<script setup>
// definePageMeta({middleware: ['auth']});

let isSend = ref(false);

const change = newValue => {
  // update the value
  isSend.value = newValue.value;
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
