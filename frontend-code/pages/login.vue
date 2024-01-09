<template>
  <div class="login_curtain flex_center">
    <div class="login_block">
      <span class="relative_100 login_title">Giriş</span>
      <div class="relative_100 login_scroll">
        <div class="relative_100">
          <!-- <a
            class="relative_100"
            :href="`${ $config.public.backendUrl }/social_login/facebook`"
          >
            <input type="hidden" name="url_address" value="" />
            <input type="hidden" name="source" value="" />
            <input type="hidden" name="url" value="" />
            <button type="button" class="relative_100 ial_button">
              <img src="/icons/facelook_logo.svg" />
              <span>Facebook ilə davam et</span>
            </button>
          </a>
          <a
            class="relative_100"
            :href="`${ $config.public.backendUrl }/social_login/google`"
          >
            <input type="hidden" name="url_address" value="" />
            <input type="hidden" name="source" value="" />
            <button type="submit" class="relative_100 ial_button">
              <img src="/icons/google_logo.svg" />
              <span>Google ilə davam et</span>
            </button>
          </a>
          <div class="relative_100 login_line">
            <span class="login_middle">ya da e-poçt ilə giriş et</span>
          </div> -->
          <!-- <form
            class="relative_100"
            @submit.prevent="login_func"
          >
              <input
                type="text"
                name="email"
                class="relative_100 input_style"
                placeholder="E-poçt"
                v-model="email"
              />
              <input
                type="password"
                name="password"
                class="relative_100 input_style"
                placeholder="Şifrə"
                v-model="password"
              />

            <button type="submit" class="relative_100 form_button main-bg-color main-bg-hover">Giriş</button>
          </form> -->

          <a
            :href="`${ $config.public.backendUrl }/sso/login`"
            type="submit"
            class="relative_100 form_button main-bg-color main-bg-hover"
            style="text-decoration: none;"
          >Giriş</a>


          <!-- <div class="relative_100 login_switcher">
            <span class="switcher_text">Profiliniz yoxdur?</span>
            <a href="/register" class="switcher main-color-purple">Qeydiyyat</a>
          </div>

          <span class="relative_100 agreement_text">
            Saytımızdan istifadə edərək siz bizim <b>Məxfilik siyasətimiz</b> və <b>Şərtlər və Qaydalarımızla</b> razılaşırsınız.
          </span> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  definePageMeta({ middleware: ["guest"] });

  useHead({
    link: [
      { href: '/css/toastify.css', rel: 'stylesheet' },
      { href: '/css/global.css', rel: 'stylesheet' },
      { href: '/css/bootstrap.min.css', rel: 'stylesheet' }
    ],
    script: [
      { src: '/js/toastify.min.js' }
    ]
  });

  const { login } = useAuth();

  let email = ref('');
  let password = ref('');

  let path = '/';
  let query_params = '';

  onMounted(() => {
    const searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('path')) {
      path = searchParams.get('path');
      searchParams.forEach((value, key) => {
        if (key != 'path') {
          query_params = `${ query_params }&${ key }=${ value }`;
        }
      });
    }
  });

  const login_func = async () => {
    let data = {
      email: email.value,
      password: password.value,
      remember: false
    }

    submitRequest(
      login(data),
      () => {
        localStorage.setItem("success_message", 'Uğurla giriş edildi');
        window.location = path + '?' + query_params + window.location.hash;
      },
      (validationErrors) => {
        for (let key in validationErrors) {
          Toastify({
            text: validationErrors[key],
            backgroundColor: "#ff0000",
            duration: 1500
          }).showToast();
        }
      }
    );
  }
</script>

<style scoped>
  .login_curtain{
    position: fixed;
    top: 0px;
    left: 0px;
    height: 100%;
    width: 100%;
    background: #00000033;
  }
  ::-webkit-scrollbar {
    width: 5px;
  }
  ::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 4px #0A2C4C33;
  }
  ::-webkit-scrollbar-thumb {
      -webkit-box-shadow: inset 0 0 4px #0A2C4C33;
  }
  .login_curtain{
    position: fixed;
    height: 100%;
    width: 100%;
    top: 0px;
    left: 0px;
    background: #00000033;
    z-index: 150;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .login_block{
    position: relative;
    width: 440px;
    max-height: 80%;
    background: #FFFFFF;
    border-radius: 12px;
    padding: 44px 0px 24px 32px;
    max-width: 95%;
  }
  .login_title{
    margin-bottom: 32px;
    font-weight: bold;
    font-size: 28px;
    line-height: 44px;
    color: #3A3A3A;
  }
  .ial_button{
    background: #FFFFFF;
    border: 1px solid rgba(112, 113, 115, 0.2);
    border-radius: 6px;
    display: flex;
    align-items: center;
    padding: 0px 16px;
    height: 48px;
    margin-bottom: 12px;
    cursor: pointer;
    white-space: nowrap;
  }
  .ial_button img{
    position: relative;
    float: left;
    width: 23px;
    margin-right: 16px;
  }
  .ial_button span{
    font-weight: 500;
    font-size: 15px;
    line-height: 20px;
    color: #3A3A3A;
  }
  .login_line{
    height: 1px;
    background: rgba(112, 113, 115, 0.2);
    margin: 8px 0px 24px 0px;
  }
  .login_middle{
    position: absolute;
    left: 50%;
    transform: translate(-50%, -50%);
    top: 0px;
    font-size: 14px;
    line-height: 130%;
    color: #707173;
    background: #ffffff;
    padding: 0px 16px;
    white-space: nowrap;
  }
  .input_style{
    height: 48px;
    line-height: 46px;
    padding: 0 14px;
    outline: none;
    font-size: 15px;
    color: #000000;
    margin: 0 0 16px 0;
    background-color: #ffffff;
    border: 1px solid rgba(112, 113, 115, 0.2);
    border-radius: 6px;
    box-shadow: none !important;
  }
  .input_style:focus{
    border: 1.5px solid #318CE7;
  }
  .form_button{
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #ffffff;
    font-weight: 500;
    font-size: 17px;
    line-height: 34px;
    outline: none;
    box-shadow: none;
    border-radius: 6px;
    border: none;
    margin: 12px 0px;
  }
  .login_switcher{
    text-align: center;
  }
  .switcher_text{
    font-size: 16px;
    line-height: 34px;
    color: #3A3A3A;
    margin-right: 8px;
  }
  .switcher{
    font-size: 16px;
    line-height: 34px;
    cursor: pointer;
  }
  .login_scroll{
    overflow: auto;
    overflow: overlay;
    padding: 0px 32px 16px 3px;
    height: calc(100% - 52px);
  }
  .agreement_text{
    font-size: 12px;
    line-height: 16px;
    color: #939393;
    text-align: center;
  }
</style>
