<template>
  <div class="main-block">
    <span class="relative_100 main-title">
      {{ tran['accountSettings'][config.lang] }}
    </span>
    <div class="relative_100 body">
      <div class="relative_left sidebar">
        <div
          class="relative_100 section flex_align_center"
          v-for="row in sections"
          @click="() => change_active_section(row.id)"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M6 12L10 8L6 4" stroke="#344054" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>

          <span :class="{ 'relative_left': true, 'active': row.active }">{{ row.name[config.lang] }}</span>
        </div>
      </div>
      <div class="relative_left content">
        <div
          v-if="active_section_id == 1 || active_section_id == 2"
          class="relative_100 list-block"
        >
          <div
            v-if="active_section_id == 1"
            v-for="(row, i) in development_status"
            :class="{ 'relative_100': true, 'list': true, 'list-2': i % 2 == 1 }"
            @click="() => change_development_status(row.id)"
          >
            {{ row.name }}
            <img
              src="/icons/check.svg"
              class="active-icon"
              v-if="row.active"
            />
          </div>

          <div
            v-if="active_section_id == 2"
            v-for="(row, i) in langs"
            :class="{ 'relative_100 list': true, 'list-2': i % 2 == 1 }"
            @click="() => change_language(row.id)"
          >
            {{ row.name }}
            <img
              src="/icons/check.svg"
              class="active-icon"
              v-if="row.id == config.lang"
            />
          </div>
        </div>

        <div
          v-if="active_section_id == 3"
          class="relative_100 appearance-main"
        >
          <div class="appearance-block flex_center">
            <div
              class="appearance-item"
              v-for="(row, i) in store.appearance"
              :style="`margin-right: ${ i == store.appearance.length - 1 ? 0 : 40 }px;`"
              @click="() => change_appearance(row.id)"
            >
              <div class="relative_100 flex_justify_center">
                <div
                  class="circle"
                  :style="`background: ${ row.code }`"
                >
                  <div
                    v-if="row.active"
                    class="active-circle"
                  ></div>
                </div>
              </div>
              <span
                :class="{ 'relative_100 appearance-text': true, 'active': row.active }"
              >{{ row.name }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="relative_100 footer">
      <div class="close-btn">
        <OneappButtonsClose
          :text="tran['close'][config.lang]"
          @close="() => store.settings_is_open = false"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
  import langs from '~/langs.json';
  import tran from '~/translates.json';
  
  const store = useStore();
  const config = useConfigStore();

  let active_section_id = ref(2);

  let sections = ref([
    // { id: 1, name: 'Development', active: true },
    { id: 2, name: tran['language'], active: true },
    { id: 3, name: tran['appearance'], active: false }
  ]);

  let development_status = ref([
    { id: 1, name: 'Development', active: true },
    { id: 2, name: 'Testing', active: false },
    { id: 3, name: 'Production', active: false }
  ]);

  const change_active_section = id => {
    active_section_id.value = id;
    sections.value = sections.value.map(row => {
      if (row.id == id) {
        row.active = true;
      } else {
        row.active = false;
      }

      return row;
    });
  }

  const change_development_status = id => {
    development_status.value = development_status.value.map(row => {
      if (row.id == id) {
        row.active = true;
      } else {
        row.active = false;
      }

      return row;
    });
  }

  const change_language = id => {
    localStorage.setItem('lang', id);
    config.toChangeLang(id);
  }

  const change_appearance = id => {
    store.change_appearance(id);
  }
</script>

<style scoped>
  .main-block{
    width: 750px;
    border-radius: 8px;
    border: 1px solid #EAECF0;
    background: #FFF;
    box-shadow: 0px 4px 6px -2px rgba(16, 24, 40, 0.03), 0px 12px 16px -4px rgba(16, 24, 40, 0.08);
  }
  .main-title{
    padding: 20px 24px 19px 24px;
    border-bottom: 1px solid #EAECF0;
    font-size: 18px;
    font-weight: 500;
    line-height: 28px;
  }
  .body{
    margin: 31px 0px;
    height: 156px;
    padding: 0px 20px;
  }
  .sidebar{
    width: 193px;
    height: 100%;
    border-right: 1px solid #F2F4F7;
  }
  .section{
    padding: 16px;
    cursor: pointer;
  }
  .section svg{
    position: relative;
    float: left;
    width: 16px;
    height: 16px;
    margin-right: 12px;
  }
  .section span{
    color: #344054;
    font-size: 16px;
    font-weight: 500;
    line-height: 20px;
  }
  .section span.active{
    font-weight: 700;
  }
  .content{
    width: calc(100% - 193px);
    padding-left: 6px;
  }
  .list-block{
    border-radius: 4px;
    border: 1px solid #F2F4F7;
  }
  .list{
    padding: 10px 14px;
    color: #101828;
    font-size: 14px;
    font-weight: 500;
    line-height: 24px;
    cursor: pointer;
  }
  .list-2{
    background: #F9FAFB;
  }
  .list .active-icon{
    position: absolute;
    right: 14px;
    width: 20px;
    height: 20px;
    object-fit: scale-down;
  }
  .footer{
    border-top: 1px solid #EAECF0;
    padding: 12px 20px 16px 20px;
  }
  .close-btn{
    position: relative;
    float: right;
    /* margin-right: 10px; */
  }
  .save-btn{
    position: relative;
    float: right;
  }
  .appearance-main{
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #F2F4F7;
    background: #FFF;
    height: 156px;
  }
  .appearance-block{
    border-radius: 6px;
    background: #FCFCFC;
    height: 100%;
  }
  .appearance-item{
    cursor: pointer;
    width: 41px;
  }
  .appearance-item .circle{
    position: relative;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    margin-bottom: 10px;
  }
  .appearance-item .active-circle{
    position: absolute;
    border: 1px solid #ccc;
    width: 34px;
    height: 34px;
    top: -2px;
    left: -2px;
    border-radius: 50%;
  }
  .appearance-text{
    color: rgba(0, 0, 0, 0.60);
    font-family: Inter;
    font-size: 14px;
    font-weight: 500;
    line-height: 20px;
    text-align: center;
  }
  .appearance-text.active{
    color: rgba(0, 0, 0, 0.87);
    font-weight: 600;
  }
</style>
