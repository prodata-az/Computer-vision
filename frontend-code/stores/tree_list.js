export const useStore = defineStore('counter', () => {
  let tree = ref([]);
  let max_id = 0;
  let show_dragged = ref(false);
  let move_dragged = ref(false);
  let start_drag = ref(false);
  let block = ref({
    node: 0,
    width: 0,
    top: 0,
    left: 0,
    type: 0,
    text: '',
  });
  let border_id = 0;
  let bottom_border_id = 0;
  let selected_id = ref(0);
  let selected_embed = ref('');

  function set_tree(val) {
    tree.value = val;
    add_parent_references();
  }

  function set_block(key, val) {
    block.value[key] = val;
  }

  function delete_node(id, save = false) {
    delete_node_by_id(tree.value, id);
    if (save) {
      save_tree();
    }
  }

  function update_name(id, value) {
    update_name_by_id(tree.value, id, value);
    save_tree();
  }

  function add_node(id) {
    add_node_by_id(tree.value, id, 2);
    add_parent_references();
    save_tree();
  }

  function add_folder(id) {
    add_node_by_id(tree.value, id, 1);
    add_parent_references();
    save_tree();
  }

  function add_main_folder() {
    get_max_id();
    max_id = max_id + 1;
    tree.value = [
      ...tree.value,
      {
        id: max_id,
        text: 'new folder',
        type: 1,
        open_edit: false,
        subs: [],
      },
    ];
    add_parent_references();
    save_tree();
  }

  const add_parent_references = (arr = tree.value, parent = null) => {
    for (let i = 0; i < arr.length; i++) {
      arr[i].parent = parent;

      if (arr[i].subs && arr[i].subs.length > 0) {
        add_parent_references(arr[i].subs, arr[i]);
      }
    }
  };

  const delete_node_by_id = (arr, id) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].id === id) {
        arr.splice(i, 1);
        i--;
      } else if (arr[i].subs && arr[i].subs.length > 0) {
        delete_node_by_id(arr[i].subs, id);
      }
    }
  };

  const update_name_by_id = (arr, id, value) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].id === id) {
        arr[i].text = value;
      } else if (arr[i].subs && arr[i].subs.length > 0) {
        update_name_by_id(arr[i].subs, id, value);
      }
    }
  };

  const add_node_by_id = (arr, id, type) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].id === id) {
        get_max_id();
        max_id = max_id + 1;
        arr[i].subs = [
          ...arr[i].subs,
          {
            id: max_id,
            text: type == 1 ? 'new folder' : 'new file',
            type: type,
            open_edit: false,
            subs: [],
          },
        ];
      } else if (arr[i].subs && arr[i].subs.length > 0) {
        add_node_by_id(arr[i].subs, id, type);
      }
    }
  };

  const get_max_id = (arr = tree.value) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].id > max_id) {
        max_id = arr[i].id;
      }

      if (arr[i].subs && arr[i].subs.length > 0) {
        get_max_id(arr[i].subs);
      }
    }
  };

  const get_boundaries = (arr = tree.value) => {
    for (let i = 0; i < arr.length; i++) {
      let element = document.getElementsByClassName(`bounds_${arr[i].id}`)[0];
      if (element) {
        let coordinates = element.getBoundingClientRect();
        arr[i].left = coordinates.left;
        arr[i].top = coordinates.top;
        arr[i].bottom = coordinates.bottom;
      } else {
        arr[i].left = 0;
        arr[i].top = 0;
        arr[i].bottom = 0;
      }

      if (arr[i].subs && arr[i].subs.length > 0) {
        get_boundaries(arr[i].subs);
      }
    }
  };

  const check_borders = (top) => {
    border_id = 0;
    bottom_border_id = 0;
    check_borders_func(top);
  };

  const check_borders_func = (top, arr = tree.value) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].hasOwnProperty('top')) {
        if (arr[i].type == 1) {
          if (top > arr[i].top && top < arr[i].bottom - 10) {
            arr[i].border = true;
            border_id = arr[i].id;
          } else {
            arr[i].border = false;
          }
        }

        if (top > arr[i].bottom - 10 && top < arr[i].bottom) {
          arr[i].bottom_border = true;
          bottom_border_id = arr[i].id;
        } else {
          arr[i].bottom_border = false;
        }
      }

      if (arr[i].subs && arr[i].subs.length > 0) {
        check_borders_func(top, arr[i].subs);
      }
    }
  };

  const end_drag = () => {
    if (start_drag.value) {
      start_drag.value = false;
      show_dragged.value = false;

      if (border_id && border_id != block.value.node.id) {
        delete_node(block.value.node.id);
        end_drag_func();
        border_id = 0;
        add_parent_references();
        save_tree();
      }

      if (bottom_border_id && bottom_border_id != block.value.node.id) {
        delete_node(block.value.node.id);
        end_drag_func();
        bottom_border_id = 0;
        save_tree();
      }
      remove_all_borders();
    }
  };

  const remove_all_borders = (arr = tree.value) => {
    for (let i = 0; i < arr.length; i++) {
      arr[i].border = false;
      arr[i].bottom_border = false;

      if (arr[i].subs && arr[i].subs.length > 0) {
        remove_all_borders(arr[i].subs);
      }
    }
  };

  const end_drag_func = (arr = tree.value) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].border) {
        arr[i].subs = [...arr[i].subs, block.value.node];
        arr[i].border = false;
        arr[i].open = true;
        setTimeout(function () {
          get_boundaries();
        }, 300);
      }

      if (arr[i].bottom_border) {
        if (arr[i].parent) {
          let new_subs = [];
          for (let j = 0; j < arr[i].parent.subs.length; j++) {
            new_subs = [...new_subs, arr[i].parent.subs[j]];
            if (arr[i].parent.subs[j].id == bottom_border_id) {
              new_subs = [...new_subs, block.value.node];
            }
          }
          arr[i].parent.subs = new_subs;
        } else {
          let new_subs = [];
          for (let j = 0; j < tree.value.length; j++) {
            new_subs = [...new_subs, tree.value[j]];
            if (tree.value[j].id == bottom_border_id) {
              new_subs = [...new_subs, block.value.node];
            }
          }
          tree.value = new_subs;
        }
        arr[i].bottom_border = false;
        setTimeout(function () {
          get_boundaries();
        }, 300);
      }

      if (arr[i].subs && arr[i].subs.length > 0) {
        end_drag_func(arr[i].subs);
      }
    }
  };

  const deepClone = (obj, cache = new WeakMap()) => {
    if (obj === null || typeof obj !== 'object') {
      return obj;
    }

    // Check if we've already cloned this object to avoid infinite loops
    if (cache.has(obj)) {
      return cache.get(obj);
    }

    if (Array.isArray(obj)) {
      const arrCopy = [];
      cache.set(obj, arrCopy);
      obj.forEach((item, index) => {
        arrCopy[index] = deepClone(item, cache);
      });
      return arrCopy;
    }

    const objCopy = {};
    cache.set(obj, objCopy);
    for (const key in obj) {
      if (obj.hasOwnProperty(key)) {
        objCopy[key] = deepClone(obj[key], cache);
      }
    }
    return objCopy;
  };

  const remove_parents = (arr) => {
    for (let i = 0; i < arr.length; i++) {
      delete arr[i].parent;
      arr[i].open = false;

      if (arr[i].subs && arr[i].subs.length > 0) {
        remove_parents(arr[i].subs);
      }
    }
  };

  const save_tree = async (show_toast = false) => {
    try {
      let tree_without_parents = deepClone(tree.value);
      remove_parents(tree_without_parents);
      await $larafetch('/api/oneapp/admin/dashboards/update', {
        method: 'post',
        body: {
          tree: tree_without_parents,
        },
      });

      if (show_toast) {
        Toastify({
          text: 'Saved',
          backgroundColor: '#47dc6b',
          duration: 1500,
        }).showToast();
      }
    } catch (e) {
      console.log(e);
      if (show_toast) {
        Toastify({
          text: 'Something went wrong',
          backgroundColor: '#ff0000',
          duration: 1500,
        }).showToast();
      }
    }
  };

  const save_embedding = (embedded_text) => {
    change_embed(embedded_text);
    save_tree(true);
  };

  const change_embed = (embedded_text, arr = tree.value) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].id == selected_id.value) {
        arr[i].embedded = embedded_text;
      }

      if (arr[i].subs && arr[i].subs.length > 0) {
        change_embed(embedded_text, arr[i].subs);
      }
    }
  };

  const get_node = (id) => {
    let getted_node = '';
    getted_node = get_node_func(id);

    return getted_node;
  };

  const get_node_func = (id, arr = tree.value) => {
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].id == id) {
        return {
          text: arr[i].text,
          embedded: arr[i].embedded,
        };
      } else if (arr[i].subs && arr[i].subs.length > 0) {
        get_node_func(id, arr[i].subs);
      }
    }
  };

  /* -------------------------------- */
  /* ------------ ONEAPP ------------ */
  /* -------------------------------- */

  let settings_is_open = ref(false);

  let appearance = ref([
    { id: 1, name: 'Green', class: 'green', code: '#32977F', active: true },
    { id: 2, name: 'Black', class: 'black', code: '#292B3A', active: false },
    { id: 3, name: 'Purple', class: 'purple', code: '#595585', active: false },
    { id: 4, name: 'Blue', class: 'blue', code: '#26798b', active: false },
  ]);

  let selected_appearance = ref('green');

  const change_appearance = (id) => {
    for (let i = 0; i < appearance.value.length; i++) {
      if (appearance.value[i].id == id) {
        appearance.value[i].active = true;
        selected_appearance.value = appearance.value[i].class;
        localStorage.setItem('selected_appearance', selected_appearance.value);
      } else {
        appearance.value[i].active = false;
      }
    }
  };

  const update_appearance = () => {
    for (let i = 0; i < appearance.value.length; i++) {
      if (appearance.value[i].class == selected_appearance.value) {
        appearance.value[i].active = true;
      } else {
        appearance.value[i].active = false;
      }
    }
  };

  return {
    tree,
    set_tree,
    delete_node,
    update_name,
    add_node,
    add_folder,
    show_dragged,
    block,
    set_block,
    move_dragged,
    start_drag,
    get_boundaries,
    check_borders,
    end_drag,
    selected_id,
    selected_embed,
    save_embedding,
    deepClone,
    get_node,
    add_main_folder,
    appearance,
    settings_is_open,
    change_appearance,
    selected_appearance,
    update_appearance,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useConfigStore, import.meta.hot));
}
