<template>
  <div
    v-if="node.subs && node.subs.length"
    class="relative_100 urls_main"
    :style="`transition: ${ menu_is_closed ? 'none' : 'all .3s ease-in-out' };`"
    :class="{ 'urlsActive': node.open }"
  >
    <span
      class="relative_100 main_span"
      @click="() => node.open = !node.open"
    >
      <i
        :class="`fa ${ node.ico } ${ node.open && menu_is_closed ? 'purple_color' : '' } main_ico`"
        aria-hidden="true"
      ></i>
      <span
        class="relative_left url_main_desc"
        v-show="!menu_is_closed || hovered"
      >
        {{ tran.hasOwnProperty(node.main) ? tran[node.main][lang] : node.main }}
      </span>
      <div v-if="!menu_is_closed || hovered">
        <i
          v-if="node.open"
          class="fa fa-angle-down angle_ico"
          aria-hidden="true"
        ></i>
        <i
          v-else
          class="fa fa-angle-right angle_ico"
          aria-hidden="true"
        ></i>
      </div>
    </span>

    <ul v-if="node.subs && node.subs.length && node.open">
      <AdminTreeNode
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
      <a :href="`/admin${ node.url }`" class="relative_100 main_span">
        <i
          :class="`fa ${ node.ico } main_ico`"
          aria-hidden="true"
        ></i>

        <span
          class="relative_left url_main_desc"
          v-show="!menu_is_closed || hovered"
        >
          {{ tran.hasOwnProperty(node.main) ? tran[node.main][lang] : node.main }}
        </span>
      </a>
    </div>

    <a
      v-if="!node.hasOwnProperty('ico')"
      :href="`/admin${ node.url }`"
    >
      <span
        class="relative_100 sub_url"
        :class="{ 'activeSubUrl': node.url == complex_url }"
      >
        <i class="fa fa-circle sub_ico" aria-hidden="true"></i>
        <span class="relative_left url_sub_desc">
          {{ tran.hasOwnProperty(node.main) ? tran[node.main][lang] : node.main }}
        </span>
        <span
          v-if="node.count !== ''"
          class="m_count"
        >{{ node.count }}</span>
      </span>
    </a>


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

  onMounted(() => {
    let url = window.location.href;
    let pos = getPosition(url, '/', 4);
    url = url.substr(pos);
    complex_url.value = url;
    lang.value = localStorage.getItem("lang") ? localStorage.getItem("lang") : 1;
  });

  // CRM sistemlər, AI portal, Fərqli dashboard
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
  .main_span{
    padding: 10px 30px;
    font-weight: 400;
    font-size: 16px;
    color: #8c8ea4;
    line-height: 20px;
  }
  .main_ico{
    position: relative;
    float: left;
    width: 35px;
    font-size: 20px;
  }
  .angle_ico{
    position: absolute;
    font-size: 13px;
    right: 30px;
    top: 14px;
  }
  .urls_main:hover{
    background: #292b3a;
  }
  .urlsActive{
    background: #292b3a;
  }
  .sub_ico{
    position: relative;
    float: left;
    width: 20px;
    font-size: 5px;
    line-height: 42px;
  }
  .sub_url{
    padding: 0px 50px 0px 35px;
    color: #686c89;
    font-size: 14px;
    line-height: 19px;
    display: flex;
    align-items: center;
  }
  .sub_url:hover{
    color: #868aa8;
  }
  .activeSubUrl{
    color: #716aca;
  }
  .activeSubUrl:hover{
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
  .url_main_desc{
    width: calc(100% - 35px);
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .url_sub_desc{
    width: calc(100% - 20px);
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
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
