<template>
  <div
    class="popup modal fade" tabindex="-1"
    id="exampleModal"
    :style="`display: ${ isVisibility ? 'block' : 'none'}`"
    :class="{
      show: isVisibility
    }"
    @click="click"
  >
    <div class="modal-dialog modal-dialog-centered">
      <form class="popup__container modal-content">
        <div class="popup__header">
          <h4>{{ tran['editLabels'][config.lang] }}</h4>
        </div>
        <div class="popup__main">
          <div class="input-block">
            <UXInput
              id="editLabel_1"
              class="form-control"
              label="label_1"
              v-model="all_values.label_1"
              placeholder="Label 1"
            />
          </div>
          <div class="input-block">
            <UXInput
              id="editLabel_2"
              class="form-control"
              label="label_2"
              v-model="all_values.label_2"
              placeholder="Label 2"
            />
          </div>
        </div>
        <div class="popup__footer">
          <UXButtonSave
            @click="toSaveChange"
          />
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
  import tran from '~/translates.json';
  import ValidateTypes from '~/classes/ValidateTypes.js';
  import axios from 'axios';

  const config = useConfigStore();
  const notify = useNotifyStore();

  let isVisibility = ref(false);
  let idOfLabel = ref('');
  let idOfUser = '';
  let firstValueOfLabel_1 = '';
  let firstValueOfLabel_2 = '';
  
  // ***** All variables for validate *****
  let button = false; // status for to activate or deactivate function validation
  let all_values = ref({
    label_1: '',
    label_2: '',
  });
  let vv = ref({
    label_1: new ValidateTypes(),
    label_2: new ValidateTypes(),
  });

  let listOfLabels = ref([]);

  const toSaveChange = async () => {
    try {
      const numberOfLabels = Object.keys(listOfLabels.value).length; // to get a number of labels
      const label_1 = all_values.value.label_1; // to get a vulue of input for first label
      const label_2 = all_values.value.label_2; // to get a vulue of input for second label
      let isSameLabels = false; // a status for dublicate lables

      // to run the validation
      validation(isVisibility);

      /*
      * if values of inputs are empty
      * show message about this error
      * exit from this function
      */
      if (!label_1 || !label_2) return notify.error(tran.fill_all_fields[config.lang]);

      // if 'label_1' and 'label_2' is same to message
      if (label_1.toString().toLowerCase() === label_2.toString().toLowerCase()) return notify.error(tran.duplicate_labels[config.lang]);

      // if list have labels to looking for dublicate labels
      if (numberOfLabels) isSameLabels = toCheckDublicate(listOfLabels.value, isSameLabels);

      // if there are dublicate labels to show message about this
      if (isSameLabels) return notify.error(tran.duplicate_labels[config.lang]);

      const { data } = await axios.post(`http://10.20.36.16:8002/api/labels/update?user_id=${idOfUser}&page=${props.currentPage}&label_id=${idOfLabel.value}`, {label_1, label_2});
      
      listOfLabels.value = data;

      emit('is-saved', listOfLabels.value);
      isVisibility.value = false;
      emit('hidden-propup', isVisibility.value);
    } catch (error) {
      console.error(error)
    }
  }

  const toCheckDublicate = (object, status) => {
    const label_1 = all_values.value.label_1.toString().toLowerCase(); // to get a vulue of input for first label
    const label_2 = all_values.value.label_2.toString().toLowerCase(); // to get a vulue of input for second label
    const firstInput = document.getElementById('label_1'); // to get first input
    const secondInput = document.getElementById('label_2'); // to get second input

    object = object.filter(element => element.id !== props.objectOfLabels.id)

    /*
     * check labels with values of inputs
     * if there are dublicate labels break iteriation
     * return status
     */
     object.forEach(element => {
      if (status) return;
      const arrayInObject = Object.values(element);
      arrayInObject.forEach(label => {
        if (typeof label === 'string') {
          if (label.toString().toLowerCase() === label_1) {
            status = true;
          } else if (label.toString().toLowerCase() === label_2) {
            status = true;
          }
        }
      });
     })

    return status;
  }

  const click = event => {
    const element = event.target; // to get the element, which to click
    const isParent = element.closest('.popup__container'); // to get the 'li' element

    // close popup and return old values of labels
    if (!isParent) {
      isVisibility.value = false;
      all_values.value['label_1'] = firstValueOfLabel_1;
      all_values.value['label_2'] = firstValueOfLabel_2;
    }

    // create a event
    emit('hidden-propup', isVisibility.value);
  }

  const validation = (button = false) => {

    // to get div element
    const divElement = document.getElementById('popup');

    if (divElement) {
      // to select all inputs with type 'text'
      const inputs = divElement.querySelectorAll('input[type="text"]');

      // if the button is not active to remove classes 'is-valid','is-valid' and to finish this function
      if (!button) {
        inputs.forEach(element => element.classList.remove('is-invalid', 'is-valid'))
        return;
      }

      /* 
      * iterating through an array of inputs and checking the values of these inputs
      * if values of inputs are empty to add class 'is-invalid'
      * if values of inputs are not empty to add class 'is-valid'
      */
      inputs.forEach(element => {
        if (element.value) {
          element.classList.remove('is-invalid');
          element.classList.add('is-valid');
        }
        else {
          element.classList.remove('is-valid');
          element.classList.add('is-invalid');
        }
      });
    }
  };

  const props = defineProps({
    show : {type: Boolean, default: false},
    arrayOfLabels : {type: Array, default: []},
    objectOfLabels: {type: Object, default: {}},
    currentPage: {type: Number, default: 1},
  });

  const emit = defineEmits([
    'is-saved',
    'hidden-propup'
  ]);

  watch(
    () => props.arrayOfLabels,
    newValue => {
      listOfLabels.value = newValue;
    },
    {deep: true}
  )

  watch(
    () => props.objectOfLabels,
    newValue => {
      all_values.value['label_1'] = newValue.label_1;
      all_values.value['label_2'] = newValue.label_2;
      idOfLabel.value = newValue.id;
      idOfUser = newValue.user_id;
      firstValueOfLabel_1 = newValue.label_1;
      firstValueOfLabel_2 = newValue.label_2;
    },
    {deep: true}
  )

  watch(
    () => props.show,
    newValue => {
      isVisibility.value = newValue;
    },
    {deep: true}
  )

  watch(
    () => all_values.value,
    () => {
      validation(button);
    },
    {deep: true}
  )
</script>

<style lang="scss" scoped>
@import "@/public/sass/variables.scss";

  .popup {
    &__container {
      margin: auto;
      background-color: $color-7;
      border-radius: 12px;
      min-width: 300px;
      min-height: 50px;
      padding: 20px;
    }
    &__main {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      row-gap: 10px;
    }
    &__footer {
      margin-top: 15px;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: end;
    }
  }
</style>