<template>
  <LoadSpinnerGears v-if="showHideSpinner" />
  <div class="relative_100 main">
    <div
      v-if="show_left_bar"
      :class="`sidebar ${menu_is_closed ? 'closed-sidebar' : ''} ${
        store.selected_appearance
      }-sidebar`"
      :style="`width: ${menu_is_closed && !hovered ? 78 : 274}px;`"
    >
      <div class="relative_100 header">
        <div class="relative_100 logo-block">
          <div
            class="icon-div flex_center"
            @click="change_menu_is_closed"
          >
            <img
              src="/icons/left-arrow.svg"
              class="icon"
            />
          </div>
          <div class="content-div">
            <NuxtLink
              to="/"
              title="To home"
            >
              <img
                class="logo"
                src="../public/images/logo_white.png"
                alt="Logo"
              />
            </NuxtLink>
          </div>
        </div>
      </div>
      <div class="relative_100 tree-block">
        <div
          v-if="false"
          class="tree"
          @mouseover="() => (hovered = true)"
          @mouseleave="() => (hovered = false)"
        >
          <AdminRedesignedTreeNode
            v-for="childNode in main_urls"
            :key="childNode.id"
            :node="childNode"
            :menu_is_closed="menu_is_closed"
            :hovered="hovered"
          />
        </div>
      </div>
      <div class="relative_100 bottom">
        <div
          class="relative_100 url-block mb_12 flex_align_center"
          @click="() => (store.settings_is_open = true)"
        >
          <img
            src="/icons/settings.svg"
            class="icon"
          />
          <span
            class="text nowrap"
            v-show="!menu_is_closed || hovered"
          >
            {{ tran['accountSettings'][config.lang] }}
          </span>
        </div>
        <div class="relative_100 url-block flex_align_center">
          <img
            src="/icons/log-out.svg"
            class="icon"
            @click="logout"
          />
          <span
            class="text nowrap"
            v-show="!menu_is_closed || hovered"
            @click="logout"
          >
            {{ tran['logout'][config.lang] }}
          </span>
        </div>
      </div>
    </div>
    <div
      class="relative_100 content"
      :style="`padding: 10px ${show_left_bar ? 10 : 0}px 10px ${
        show_left_bar ? (menu_is_closed ? 83 : 284) : 0
      }px;`"
    >
      <!-- <br> -->
      <div class="relative_100">
        <slot></slot>
      </div>
    </div>
  </div>

  <OneappCurtain
    v-if="store.settings_is_open"
    @close="() => (store.settings_is_open = false)"
  >
    <OneappAccountSettings />
  </OneappCurtain>
</template>

<script setup>
  import tran from '~/translates.json';

  const store = useStore();
  const config = useConfigStore();

  const { logout } = useAuth();

  let show_left_bar = ref(true);
  const url = useRequestURL()
  if (url.searchParams.has('mode')) {
    let mode = url.searchParams.get('mode');
    if (mode == 'embed') {
      show_left_bar.value = false;
    }
  }

  let showHideSpinner = ref(true);

  let companies = [
    {
      id: 1,
      name: 'Development',
    },
    {
      id: 2,
      name: 'Testing',
    },
    {
      id: 3,
      name: 'Production',
    },
  ];
  let selected_company = ref(companies[0].id);

  let companies_block_show = ref(0);

  const change_company = async (id, template_id) => {
    selected_company.value = id;
    companies_block_show.value = 0;
  };

  useHead({
    link: [
      { href: '/images/favicon.svg', rel: 'icon' },
      { href: '/css/global.css', rel: 'stylesheet' },
      { href: '/css/redesigned_oneapp.css', rel: 'stylesheet' },
      { href: '/css/font-awesome.min.css', rel: 'stylesheet' },
      { href: '/css/bootstrap.min.css', rel: 'stylesheet' },
      { href: '/css/admin.css', rel: 'stylesheet' },
    ],
  });

  let main_urls = ref([
    {
      id: 1,
      text: 'Computer Vision',
      type: 1,
      ico: 'bar-chart.svg',
      open: false,
      subs: [
        {
          id: 2,
          text: 'Train',
          type: 2,
          subs: [],
          url: '/train',
        },
        {
          id: 3,
          text: 'Retrain',
          type: 2,
          subs: [],
          url: '/retrain',
        },
      ],
    },
  ]);

  //Powered by OneApp Technology

  let menu_is_closed = ref(true);
  let hovered = ref(false);

  const change_menu_is_closed = async () => {
    menu_is_closed.value = !menu_is_closed.value;
    localStorage.setItem('menu_is_closed', menu_is_closed.value);
  };

  function getPosition(string, subString, index) {
    return string.split(subString, index).join(subString).length;
  }

  onMounted(() => {
    localStorage.getItem('lang')
      ? config.toChangeLang(localStorage.getItem('lang'))
      : config.toChangeLang(1);
    menu_is_closed.value =
      localStorage.getItem('menu_is_closed') === null
        ? true
        : JSON.parse(localStorage.getItem('menu_is_closed'));
    store.selected_appearance = localStorage.getItem('selected_appearance')
      ? localStorage.getItem('selected_appearance')
      : store.selected_appearance;
    store.update_appearance();
    setTimeout(function () {
      showHideSpinner.value = false;
    }, 300);

    window.addEventListener('click', (e) => window_click(e));
    let url = window.location.href;
    let full_url = url;
    let split_url = url.split('/');

    let pos = getPosition(url, '/', 3);
    url = url.substr(pos);

    main_urls.value.map((row) => {
      updateOpenValueByUrl(row, url, full_url);
    });

    var mainUrl = window.location.href;
    var hasModeEmbed = mainUrl.includes('mode=embed');

    if (hasModeEmbed) {
      var links = document.querySelectorAll('a');
      links.forEach(function (link) {
        var currentHref = link.getAttribute('href');
        var newHref = currentHref + (currentHref.includes('?') ? '&mode=embed' : '?mode=embed');
        link.setAttribute('href', newHref);
      });
    }
  });

  function addParentReferences(obj, parent = null) {
    obj.parent = parent;
    if (obj.subs) {
      for (const subObj of obj.subs) {
        addParentReferences(subObj, obj);
      }
    }
  }

  main_urls.value.map((row) => {
    addParentReferences(row);
  });

  const route = useRoute();
  let node_id = parseInt(route.hash.replace('#', ''));

  function updateOpenValueByUrl(obj, targetUrl, fullUrl) {
    if (
      obj.hasOwnProperty('url') &&
      (obj.url === targetUrl || obj.url == fullUrl)
    ) {
      obj.open = true;
      updateParentOpenValues(obj);
    } else if (!obj.hasOwnProperty('img') && obj.id == node_id) {
      obj.open = true;
      updateParentOpenValues(obj);
    } else if (obj.hasOwnProperty('img') && obj.id == node_id) {
      obj.open = true;
      updateParentOpenValues(obj);
    } else if (obj.subs) {
      for (const subObj of obj.subs) {
        updateOpenValueByUrl(subObj, targetUrl, fullUrl);
      }
    }
  }

  function updateParentOpenValues(obj) {
    if (obj.parent) {
      obj.parent.open = true;
      updateParentOpenValues(obj.parent);
    }
  }

  const open_url = (url) => {
    main_urls.value = main_urls.value.map((n) => {
      if (n.main == url.main) {
        n.open = !n.open;
      } else {
        n.open = false;
      }

      return n;
    });
  };

  const open_sub_url = (url, row) => {
    main_urls.value = main_urls.value.map((n) => {
      if (n.main == url.main) {
        n.subs = n.subs.map((m) => {
          if (m.main == row.main) {
            m.open = !m.open;

            if (m.open) {
              n.height = m.subs.length * 42;
            } else {
              n.height = 0;
            }
          } else {
            m.open = false;
          }

          return m;
        });
      }

      return n;
    });
  };

  let langs_block_show = ref(0);

  const window_click = (e) => {
    let langs_main_block = document.getElementById('langs_main_block');

    if (langs_main_block) {
      if (e.target != langs_main_block && !langs_main_block.contains(e.target))
        langs_block_show.value = 0;
    }

    let companies_main_block = document.getElementById('companies_main_block');

    if (companies_main_block) {
      if (
        e.target != companies_main_block &&
        !companies_main_block.contains(e.target)
      )
        companies_block_show.value = 0;
    }
  };
</script>

<style scoped>
  .main {
    height: 100%;
    margin: 0px;
    background: #f2f3f8;
  }
  .sidebar {
    position: fixed;
    top: 0px;
    left: 0px;
    height: 100%;
    background: #32977f;
    padding: 24px 16px;
    overflow: auto;
    -webkit-transition: width 0.2s ease;
    transition: width 0.2s ease;
    z-index: 100;
    font-family: Inter;
    overflow: hidden;
  }
  .green-sidebar {
    background: #32977f;
  }
  .black-sidebar {
    background: #292b3a;
  }
  .purple-sidebar {
    background: #595585;
  }
  .blue-sidebar {
    background: #26798b;
  }
  .content {
    padding: 16px 20px 10px 275px;
    /* overflow: auto; */
    -webkit-transition: all 0.2s ease;
    transition: all 0.2s ease;
  }
  .content_title_ico {
    position: relative;
    float: left;
    color: #898b96;
    font-size: 20px;
    line-height: 40px;
    width: 35px;
    margin-left: 10px;
  }
  .content_title {
    position: relative;
    float: left;
    font-size: 20px;
    padding-left: 25px;
    line-height: 40px;
    border-left: 1px solid #e2e5ec;
    font-weight: 400;
    color: #3f4047;
  }
  .header {
    top: 0px;
    left: 0px;
    margin-bottom: 8px;
  }
  .logo-block {
    height: 65px;
    border-radius: 8px;
    padding: 12px;
    border: 1px solid #fff;
    display: flex;
    gap: 12px;
  }
  .logo-block .icon-div {
    position: relative;
    float: left;
    width: 26px;
    height: 100%;
    cursor: pointer;
  }
  .logo-block .icon {
    width: 13px;
    transition: 0.2s ease;
  }
  .closed-sidebar .logo-block .icon {
    transform: rotate(180deg);
  }
  .logo-block .content-div {
    position: relative;
    float: left;
    width: calc(100% - 36px);
    font-weight: 600;
    display: flex;
    align-items: center;
  }
  .logo-block .main-text {
    color: #fff;
    font-size: 18px;
    line-height: 22px;
    margin-bottom: 4px;
  }
  .logo-block .desc {
    font-size: 12px;
    line-height: 15px;
    color: #e9e9e9;
  }

  .logo {
    max-width: 100%;
  }

  .tree-block {
    overflow-y: auto;
    height: calc(100% - 165px);
  }
  .tree-block::-webkit-scrollbar {
    width: 5px;
  }
  .tree-block::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 4px #0a2c4c33;
  }
  .tree-block::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 4px #0a2c4c33;
  }
  .url-block {
    padding: 10px;
    height: 40px;
    cursor: pointer;
  }
  .mb_12 {
    margin-bottom: 12px;
  }
  .url-block .icon {
    width: 20px;
    height: 20px;
    object-fit: scale-down;
    margin-right: 8px;
  }
  .url-block .text {
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    width: calc(100% - 28px);
  }
  .bottom {
    border-top: 1px solid #59ab97;
  }
  .purple-sidebar .bottom {
    border-color: #a39edd;
  }
  .blue-sidebar .bottom {
    border-color: #4496a7;
  }
  .black-sidebar .bottom {
    border-color: #565a75;
  }
</style>
