import { toast } from 'vue3-toastify';

export const useNotifyStore = defineStore('notifyStore', () => {
  const autoClose = ref(3000);
  const pauseOnFocusLoss = ref(false);
  const delay = ref(0);
  const position = ref(toast.POSITION.TOP_RIGHT);
  const transition = ref(toast.TRANSITIONS.ZOOM); // ZOOM, FLIP, BOUNCE, SLIDE
  const multiple = ref(true);
  const rtl = ref(false);

  const info = (text) => {
    toast.info(text, {
      autoClose: autoClose.value,
      pauseOnFocusLoss: pauseOnFocusLoss.value,
      delay: delay.value,
      position: position.value,
      transition: transition.value,
      multiple: multiple.value,
      rtl: rtl.value,
    });
  };

  const error = (text) => {
    toast.error(text, {
      autoClose: autoClose.value,
      pauseOnFocusLoss: pauseOnFocusLoss.value,
      delay: delay.value,
      position: position.value,
      transition: transition.value,
      multiple: multiple.value,
      rtl: rtl.value,
    });
  };

  const warning = (text) => {
    toast.warning(text, {
      autoClose: autoClose.value,
      pauseOnFocusLoss: pauseOnFocusLoss.value,
      delay: delay.value,
      position: position.value,
      transition: transition.value,
      multiple: multiple.value,
      rtl: rtl.value,
    });
  };

  const success = (text) => {
    toast.success(text, {
      autoClose: autoClose.value,
      pauseOnFocusLoss: pauseOnFocusLoss.value,
      delay: delay.value,
      position: position.value,
      transition: transition.value,
      multiple: multiple.value,
      rtl: rtl.value,
    });
  };

  const loading = (id, text) => {
    toast.loading(text, {
      toastId: id,
    });
  };

  const update = (id, text, type) => {
    toast.update(id, {
      render: text,
      autoClose: autoClose.value,
      closeOnClick: true,
      closeButton: true,
      type,
      isLoading: false,
      position: position.value,
      transition: transition.value,
      multiple: multiple.value,
      rtl: rtl.value,
    });
  };

  return {
    info,
    success,
    error,
    warning,
    loading,
    update,
  };
});

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useNotifyStore, import.meta.hot));
}
