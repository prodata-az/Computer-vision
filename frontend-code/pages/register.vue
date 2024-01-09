<template>
  <LoadSpinnerGears v-if="showHideSpinner" />
  <div class="login_curtain flex_center">
    <div class="login_block">
      <span class="relative_100 login_title">
        {{ tran['register'][config.lang] }}
      </span>
      <div class="relative_100 login_scroll">
        <div class="relative_100" style="display: flex; flex-direction: column; gap: 16px;">
          <form
            class="relative_100"
            @submit.prevent="sign_up_func"
          >
            <input
              type="text"
              name="name"
              autocomplete="on"
              :class="`relative_100 input_style mb_16 form-control ${ name_valid_text }`"
              :placeholder="tran['name'][config.lang]"
              v-model="name"
            >
            <!-- <small
              v-if="validate == 0 && name_is_valid == 0"
              :class="`${ name_status_text } relative_100 validate_message`"
            >{{ name_message }}</small> -->

            <input
              type="email"
              name="email"
              autocomplete="on"
              :class="`relative_100 input_style mb_16 form-control ${ mail_valid_text }`"
              :placeholder="tran['email'][config.lang]"
              v-model="email"
            >
            <!-- <small
              v-if="validate == 0 && mail_is_valid == 0"
              :class="`${ mail_status_text } relative_100 validate_message`"
            >{{ mail_message }}</small> -->

            <input
              type="password"
              name="password"
              autocomplete="off"
              :class="`relative_100 input_style mb_16 form-control ${ password_valid_text }`"
              :placeholder="tran['password'][config.lang]"
              v-model="password"
            >
            <!-- <small
              v-if="validate == 0 && password_is_valid == 0"
              :class="`${ password_status_text } relative_100 validate_message`"
            >{{ password_message }}</small> -->

            <input
              type="password"
              name="password"
              autocomplete="off"
              :class="`relative_100 input_style mb_16 form-control ${ repeat_password_valid_text }`"
              :placeholder="tran['confirmPassword'][config.lang]"
              v-model="confirm_password"
            >
            <!-- <small
              v-if="validate == 0 && repeat_password_is_valid == 0"
              :class="`${ repeat_password_status_text } relative_100 validate_message`"
            >{{ repeat_password_message }}</small> -->

            <UXButton
              type="submit"
              class="relative_100"
              style="width: 100%;"
            >
              {{ tran['register'][config.lang] }}
            </UXButton>
          </form>

          <div class="relative_100 login_switcher">
            <span class="switcher_text">
              {{ tran['alreadyHaveAccount'][config.lang] }}
            </span>
            <a href="/login" class="switcher main-color-purple">
              {{ tran['login'][config.lang] }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import tran from '~/translates.json';

// definePageMeta({middleware: ['guest']});

useHead({
  link: [
    { href: '/css/toastify.css', rel: 'stylesheet' },
    { href: '/css/global.css', rel: 'stylesheet' },
    { href: '/css/bootstrap.min.css', rel: 'stylesheet' }
  ],
});

const { register } = useAuth();
const notify = useNotifyStore();
const config = useConfigStore();

let showHideSpinner = ref(true);

let email = ref('');
let password = ref('');
let confirm_password = ref('');
let name = ref('');

let validate = 2;

let name_is_valid = ref(0);
let name_message = ref('');
let name_valid_text = ref('');
let name_status_text = ref('');

let mail_is_valid = ref(0);
let mail_message = ref('');
let mail_valid_text = ref('');
let mail_status_text = ref('');

let password_is_valid = ref(0);
let password_message = ref('');
let password_valid_text = ref('');
let password_status_text = ref('');

let repeat_password_is_valid = ref(0);
let repeat_password_message = ref('');
let repeat_password_valid_text = ref('');
let repeat_password_status_text = ref('');

const registerValidateFunc = (button = false) => {
if (button)
  validate = 0;

if (validate == 0 || validate == 1) {
  if (name.value.length == 0) {
    name_is_valid.value = 0;
    name_message.value = 'This field is required';
    name_valid_text.value = 'is-invalid';
    name_status_text.value = 'text-danger';
  } else {
    name_is_valid.value = 1;
    name_valid_text.value = 'is-valid';
    name_status_text.value = 'text-success';
  }

  if (email.value.length == 0) {
    mail_is_valid.value = 0;
    mail_message.value = 'This field is required';
    mail_valid_text.value = 'is-invalid';
    mail_status_text.value = 'text-danger';
  } else {
    mail_is_valid.value = 1;
    mail_valid_text.value = 'is-valid';
    mail_status_text.value = 'text-success';
  }

  if (password.value.length == 0) {
    password_is_valid.value = 0;
    password_message.value = 'This field is required';
    password_valid_text.value = 'is-invalid';
    password_status_text.value = 'text-danger';
  } else if (password.value.length < 6) {
    password_is_valid.value = 0;
    password_message.value = 'Minimum 6 symbols';
    password_valid_text.value = 'is-invalid';
    password_status_text.value = 'text-danger';
  } else {
    password_is_valid.value = 1;
    password_valid_text.value = 'is-valid';
    password_status_text.value = 'text-success';
  }

  if (confirm_password.value.length == 0) {
    repeat_password_is_valid.value = 0;
    repeat_password_message.value = 'This field is required';
    repeat_password_valid_text.value = 'is-invalid';
    repeat_password_status_text.value = 'text-danger';
  } else if (confirm_password.value != password.value) {
    repeat_password_is_valid.value = 0;
    repeat_password_message.value = 'Passwords are not match';
    repeat_password_valid_text.value = 'is-invalid';
    repeat_password_status_text.value = 'text-danger';
  } else {
    repeat_password_is_valid.value = 1;
    repeat_password_valid_text.value = 'is-valid';
    repeat_password_status_text.value = 'text-success';
  }

  if (name_is_valid.value && mail_is_valid.value && password_is_valid.value && repeat_password_is_valid.value)
    validate = 1;
}
}

watch(name, (new_, old) => registerValidateFunc());
watch(email, (new_, old) => registerValidateFunc());
watch(password, (new_, old) => registerValidateFunc());
watch(confirm_password, (new_, old) => registerValidateFunc());

const sign_up_func = async () => {
  // registerValidateFunc(true);

  // if (validate == 1)
  // {

    let data = {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: confirm_password.value
    }

    await submitRequest(
      register(data),
      () => {
        localStorage.setItem("success_message", tran['succesRegister'][config.lang]);
        notify.success(tran['succesRegister'][config.lang]);
        window.location.reload();
      },
      (validationErrors) => {
        for (let key in validationErrors) {
          notify.error(validationErrors[key]);
        }
      }
    );
    registerValidateFunc(true);
  // }
}

onMounted(() => {
  localStorage.getItem('lang')
  ? config.toChangeLang(localStorage.getItem('lang'))
  : config.toChangeLang(1);

  showHideSpinner.value = false;
})
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
.validate_message{
  position: relative;
  top: -16px;
}
.text-danger{
  color: #dc3545 !important;
}

/* @media screen and (max-height: 800px) {
  .login_block{
    height: 80%;
  }
} */
</style>
