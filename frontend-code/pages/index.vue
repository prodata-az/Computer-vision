<template>
  <NuxtLayout name="main">
    <div class="container">
      <main>
        <div class="wrapper">
          <div class="wrapper__icon">
            <img
              src="~/public/images/dron.png"
              alt="Dron icon"
            />
          </div>
          <div class="wrapper__content">
            <div class="wrapper__title">
              Welcome to Computer Vision Control Panel !
            </div>
            <div class="wrapper__description">
              Step into the future of visual intelligence with our Computer
              Vision Control Panel – your passport to a world where machines
              see, understand, and interact with the visual world around them
            </div>
            <div class="wrapper__button">
              <a href="/models">
                <UXButton class="btn"> Let's go! </UXButton>
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>
  </NuxtLayout>
</template>

<script setup></script>

<style lang="scss" scoped>
  @import '~/public/sass/variables.scss';
  @import '~/public/css/reset.css';

  .container {
    width: 100%;
    height: calc(100vh - 20px);
    margin: 0;
    padding: 40px;
    border-radius: 8px;
    background-color: $color-2;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.06),
      0px 1px 3px 0px rgba(16, 24, 40, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  main {
    width: 100%;
    height: 100%;
  }
  .wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 40px;
    &__icon {
      width: 150px;
      height: 121px;
      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
    &__content {
      width: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 20px;
    }
    &__title {
      max-width: 571px;
      color: #000;
      font-family: Inter;
      font-size: 2rem;
      font-weight: 700;
      line-height: 3rem;
      text-align: center;
    }
    &__description {
      max-width: 824px;
      color: rgba(0, 0, 0, 0.38);
      text-align: center;
      font-family: Inter;
      font-size: 1rem;
      font-weight: 500;
      line-height: 1.5rem;
    }
    &__button {
      .btn {
        min-width: 180px;
        padding: 10px 16px;
        background-color: $color-3;
        border: none;
        color: $color-2;
        &:hover {
          color: $color-2;
          background-color: $color-3;
        }
      }
    }
  }
</style>
