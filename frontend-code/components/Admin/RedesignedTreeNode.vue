<template>
  <div
    v-if="node.subs && node.subs.length"
    class="relative_100 urls_main"
    :style="`transition: ${ menu_is_closed ? 'none' : 'all .3s ease-in-out' };`"
  >
    <span
      class="relative_100 main_span"
      @click="() => node.open = !node.open"
    >
      <img
        v-if="node.hasOwnProperty('ico')"
        :src="`/icons/${ node.ico }`"
        class="main_ico"
      >
      <img
        v-else
        src="/icons/folder.svg"
        class="main_ico"
      >
      <span
        class="relative_left url_main_desc"
        v-show="!menu_is_closed || hovered"
      >
        {{ tran.hasOwnProperty(node.text) ? tran[node.text][lang] : node.text }}
      </span>
      <div
        v-if="!menu_is_closed || hovered"
        class="ico-div"
      >
        <img
          v-if="node.open"
          src="/icons/chevron-down.svg"
          class="angle_ico"
        />
        <img
          v-else
          src="/icons/chevron-right.svg"
          class="angle_ico"
        />
      </div>
    </span>

    <ul v-if="(!menu_is_closed || hovered) && node.subs && node.subs.length && node.open">
      <AdminRedesignedTreeNode
        v-for="childNode in node.subs"
        :key="childNode.id"
        :node="childNode"
      />
    </ul>
  </div>

  <div class="relative_100" v-else >
    <div
      v-show="node.hasOwnProperty('ico')"
      class="relative_100 urls_main"
    >
      <a
        v-if="node.hasOwnProperty('url')"
        :href="node.url"
        :class="`relative_100 main_span ${ complex_url == node.url ? 'active-url' : '' }`"
      >
        <!-- <img
          :src="`/icons/${ node.ico }`"
          class="main_ico"
        > -->

        <span
          class="relative_left url_main_desc"
          v-show="!menu_is_closed || hovered"
        >
          {{ tran.hasOwnProperty(node.text) ? tran[node.text][lang] : node.text }}
        </span>
      </a>

      <div
        v-else
        class="relative_100 main_span"
      >
        <img
          :src="`/icons/${ node.ico }`"
          class="main_ico"
        >

        <span
          class="relative_left url_main_desc"
          v-show="!menu_is_closed || hovered"
        >
          {{ tran.hasOwnProperty(node.text) ? tran[node.text][lang] : node.text }}
        </span>
      </div>
    </div>

    <div
      v-if="!node.hasOwnProperty('ico')"
    >
      <a
        v-if="node.hasOwnProperty('url')"
        :href="node.url"
        :class="`relative_100 sub_url ${ complex_url == node.url ? 'active-url' : '' }`"
      >
        <img
          src="/icons/disc.svg"
          class="main_ico small_ico"
        />
        <span class="relative_left url_sub_desc">
          {{ tran.hasOwnProperty(node.text) ? tran[node.text][lang] : node.text }}
        </span>
      </a>

      <a
        v-else-if="node.hasOwnProperty('img')"
        :class="`relative_100 sub_url ${ complex_url == `/oneapp/under_construction#${ node.id }` ? 'active-url' : '' }`"
        :href="`/oneapp/under_construction#${ node.id }`"
      >
        <img
          src="/icons/disc.svg"
          class="main_ico small_ico"
        />

        <span class="relative_left url_sub_desc">
          {{ tran.hasOwnProperty(node.text) ? tran[node.text][lang] : node.text }}
        </span>
      </a>

      <a
        v-else
        :href="`/oneapp/dashboards#${ node.id }`"
        :class="`relative_100 sub_url ${ complex_url == `/oneapp/dashboards#${ node.id }` ? 'active-url' : '' }`"
      >
        <img
          src="/icons/disc.svg"
          class="main_ico small_ico"
        >

        <span
          class="relative_left url_sub_desc"
          v-show="!menu_is_closed || hovered"
        >
          {{ tran.hasOwnProperty(node.text) ? tran[node.text][lang] : node.text }}
        </span>
      </a>
    </div>
  </div>

</template>

<script setup>
  import tran from '~/translates.json';

  const props = defineProps(['node', 'menu_is_closed', 'hovered']);

  let lang = ref(1);
  let complex_url = ref('');

  function getPosition(string, subString, index) {
    return string.split(subString, index).join(subString).length;
  }

  const get_url = () => {
    let url = window.location.href;
    let pos = getPosition(url, '/', 3);
    url = url.substr(pos);
    complex_url.value = url;
  }

  onMounted(() => {
    get_url();
    lang.value = localStorage.getItem("lang") ? localStorage.getItem("lang") : 1;
    window.addEventListener('hashchange', function () {
      get_url();
    });
  });
</script>

<style scoped>
  .tree-node {
    margin-left: 10px;
  }
  .main{
    height: 100%;
    margin: 0px;
    background: #f2f3f8;
  }
  .head{
    position: fixed;
    top: 0px;
    left: 0px;
    height: 70px;
    width: 100%;
    z-index: 100;
  }
  .left_side{
    position: relative;
    float: left;
    background: #282a3c;
    height: 100%;
    padding: 0px 30px;
    display: flex;
    align-items: center;
    -webkit-transition: width .2s ease;
    transition: width .2s ease;
  }
  .right_side{
    position: relative;
    float: left;
    background: #ffffff;
    height: 100%;
    -webkit-box-shadow: 0 1px 15px 1px rgb(69 65 78 / 10%);
    box-shadow: 0 1px 15px 1px rgb(69 65 78 / 10%);
    -webkit-transition: width .2s ease;
    transition: width .2s ease;
  }
  .main_logo{
    position: relative;
    height: 25px;
    left: -15px;
    opacity: 0.7;
  }
  .left_bottom{
    position: fixed;
    top: 70px;
    left: 0px;
    height: 100%;
    height: calc(100% - 70px);
    background: #2c2e3e;
    padding-top: 30px;
    overflow: auto;
    -webkit-transition: width .2s ease;
    transition: width .2s ease;
  }
  .left_bottom::-webkit-scrollbar {
    width: 5px;
  }
  .left_bottom::-webkit-scrollbar-track {
      -webkit-box-shadow: inset 0 0 4px #0A2C4C33;
  }
  .left_bottom::-webkit-scrollbar-thumb {
      -webkit-box-shadow: inset 0 0 4px #0A2C4C33;
  }
  .urls_main{
    cursor: pointer;
    overflow: hidden;
  }
  .main_span, .sub_url{
    padding: 10px;
    height: 40px;
    font-weight: 500;
    font-size: 16px;
    color: #fff;
    line-height: 19px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    border-radius: 8px;
  }
  .sub_url:hover{
    background: #59AB97;
  }
  .purple-sidebar .sub_url:hover{
    background: #A39EDD;
  }
  .blue-sidebar .sub_url:hover{
    background: #4496A7;
  }
  .black-sidebar .sub_url:hover{
    background: #565A75;
  }
  .active-url{
    border: 1px solid #ECECEC !important;
    background: #FFF !important;
    color: #000 !important;
  }
  .active-url .main_ico{
    filter: invert(1);
  }
  .main_ico{
    position: relative;
    float: left;
    width: 20px;
    height: 20px;
    object-fit: scale-down;
    margin-right: 8px;
  }
  .small_ico{
    width: 15px;
    height: 15px;
  }
  .ico-div{
    position: absolute;
    right: 10px;
  }
  .angle_ico{
    width: 20px;
    height: 20px;
    object-fit: scale-down;
  }
  .sub_ico{
    position: relative;
    float: left;
    width: 20px;
    font-size: 5px;
    line-height: 42px;
  }
  /* .sub_url{
    padding: 0px 50px 0px 35px;
    color: #686c89;
    font-size: 14px;
    line-height: 19px;
    display: flex;
    align-items: center;
  } */
  .activeSubUrl{
    color: #716aca;
  }
  .content{
    padding: 80px 20px 10px 275px;
    overflow: auto;
    -webkit-transition: all .2s ease;
    transition: all .2s ease;
  }
  .content_title_ico{
    position: relative;
    float: left;
    color: #898b96;
    font-size: 20px;
    line-height: 40px;
    width: 35px;
    margin-left: 10px;
  }
  .content_title{
    position: relative;
    float: left;
    font-size: 20px;
    padding-left: 25px;
    line-height: 40px;
    border-left: 1px solid #e2e5ec;
    font-weight: 400;
    color: #3f4047;
  }
  .mb_10{
    margin-bottom: 10px;
  }
  .langs_main_block, .companies_main_block{
    position: relative;
    float: left;
    margin-left: 10px;
    padding: 0px 20px;
    height: 70px;
  }
  .lang_span, .company_span{
    position: relative;
    float: left;
    font-size: 14px;
    color: #676c7b;
    line-height: 70px;
    display: flex;
    align-items: center;
    cursor: pointer;
  }
  .lang_span:hover, .lang_sub_span:hover,
  .company_span:hover, .company_sub_span:hover{
    color: #716aca;
  }
  .lang_span i, .company_span i{
    width: 16px;
    text-align: right;
  }
  .langs_block, .companies_block{
    position: absolute;
    left: 0px;
    top: 78px;
    background: #fff;
    padding: 10px 0px;
    -webkit-box-shadow: 0 0 15px 1px rgb(69 65 78 / 20%);
    box-shadow: 0 0 15px 1px rgb(69 65 78 / 20%);
    border-radius: 4px;
    transition: .3s ease;
  }
  .langs_block::after, .companies_block::after{
    content: '';
    position: absolute;
    left: 47px;
    top: -17px;
    border: 9px solid transparent;
    border-bottom: 8px solid #fff;
  }
  .lang_sub_span, .company_sub_span{
    padding: 9px 30px;
    font-size: 14px;
    cursor: pointer;
    color: #676c7b;
    display: flex;
    align-items: center;
  }
  .companies_main_block{
    margin-left: 16px;
  }
  .open_close_menu{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 20px;
    width: 26px;
    cursor: pointer;
    padding: 7px 0px;
  }
  .open_close_menu span{
    height: 1px;
    min-height: 1px;
    width: 100%;
    border-radius: 0;
    -webkit-transition: all .4s ease;
    transition: all .4s ease;
    background: #5d5f77;
  }
  .open_close_menu:hover span{
    background: #716aca;
  }
  .open_close_menu span::before, .open_close_menu span::after{
    position: absolute;
    display: block;
    left: auto;
    right: 0;
    width: 50%;
    height: 1px;
    min-height: 1px;
    content: "";
    border-radius: 0;
    -webkit-transition: all .4s ease;
    transition: all .4s ease;
    background: #5d5f77;
  }
  .open_close_menu.closed span::before, .open_close_menu.closed span::after{
    left: 0;
    right: auto;
  }
  .open_close_menu span::before{
    top: -7px;
  }
  .open_close_menu span::after{
    bottom: -7px;
  }
  .open_close_menu:hover span::before, .open_close_menu:hover span::after{
    background: #716aca;
    width: 100%;
  }
  .display_none{
    display: none;
  }
  .purple_color{
    color: #716aca;
  }
  .url_main_desc, .url_sub_desc{
    width: calc(100% - 46px);
  }
  .url_main_desc{
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }
  .sub_mains_div{
    width: 100%;
    padding-left: 24px;
    padding-right: 10px;
  }
  ul{
    padding-left: 1rem;
  }
</style>
