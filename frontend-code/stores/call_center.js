import { defineStore } from 'pinia'

export const useStore = defineStore('counter', () => {
  let history = ref([]);
  let last_id = ref('');
  let id_now = ref('');
  let session_open_state = ref(false);
  let opened_session_id = ref('');
  let active_page_id = ref(0);
  let sender = ref('');

  const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];

  watch(history, (old, _new) => {
    session_open_state.value = false;
    history.value.map(row => {
      if (row.open) {
        session_open_state.value = true;
      }
    });
  });

  const toggle_history_session = id => {
    id_now.value = 1;

    history.value = history.value.map(row => {
      if (row.id == id) {
        row.open = !row.open;
        if (row.open) {
          opened_session_id.value = row.id;
          let sub_now = row.subs.find(row => row.id == id_now.value);
          if (sub_now.type == 1 && sub_now.input) {
            load_audio(row, id_now.value);
          }
        }
        last_id.value = row.subs[row.subs.length - 1].id;
      } else {
        row.open = false;
      }

      return row;
    });
  }

  const add_history_session = () => {
    let new_id = 1;
    if (history.value.length) {
      new_id = history.value[history.value.length - 1].id + 1
    }

    last_id.value = 1;
    id_now.value = 1;

    history.value = history.value.map(row => {
      row.open = false;
      return row;
    });

    let date = new Date();
    let title = date.getDate() + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear() + ' Session ' + new_id;

    let new_session = {
      id: new_id,
      title: title,
      open: true,
      subs: [
        {
          id: 1,
          type: 1,
          title: 'Voice to text',
          performance: 0,
          time: 0,
          input: '',
          output: '',
          is_wav: '',
          active: false
        }
      ]
    }

    history.value = [...history.value, new_session];

    opened_session_id.value = new_id;

    return new_session;
  }

  const add_history = (session_id, val) => {
    history.value = history.value.map(row => {
      if (row.id == session_id) {
        row.subs = [...row.subs, val];
      }

      return row;
    });

    if (history.value.find(row => row.id == session_id).open) {
      last_id.value = val.id;
      id_now.value = val.id;
    }
  }

  const update_history = (session_id, id, val) => {
    history.value = history.value.map(row => {
      if (row.id == session_id) {
        row.subs = row.subs.map(raw => {
          if (raw.id == id) {
            raw.performance = val.performance;
            raw.time = val.time;
            raw.input = val.input;
            raw.output = val.output;
            raw.active = true;
            raw.is_wav = val.hasOwnProperty('is_wav') ? val.is_wav : '';
          }

          return raw;
        });
      }

      return row;
    });
  }

  const change_last_id = val => {
    last_id.value = val;
  }

  const change_id_now = val => {
    id_now.value = val;
    let open_session = history.value.find(row => row.id == opened_session_id.value);
    let sub_now = open_session.subs.find(row => row.id == id_now.value);
    if (sub_now.type == 1 && sub_now.input) {
      load_audio(open_session, id_now.value);
    }
    if (sub_now.type == 4 && sub_now.output) {
      load_audio(open_session, id_now.value, true);
    }
  }

  const load_audio = (session, id, is_tts=false) => {
    let recorderAudioAsBlob = session.subs.find(row => row.id == id).input;
    if (is_tts) {
      recorderAudioAsBlob = session.subs.find(row => row.id == id).output;
    }

    let reader = new FileReader();

    reader.onload = (e) => {
        let base64URL = e.target.result;
        let voice_type = is_tts ? 'tts' : 'asr';
        let audioElement = document.getElementById(`${ voice_type }_audio_${ session.id }_${ id_now.value }`);
        let audioElementSource = audioElement.getElementsByTagName("source")[0];

        audioElementSource.src = base64URL;

        if (session.subs.find(row => row.id == id).is_wav) {
          audioElementSource.type = 'audio/wav';
        } else {
          let BlobType = recorderAudioAsBlob.type.includes(";") ?
              recorderAudioAsBlob.type.substr(0, recorderAudioAsBlob.type.indexOf(';')) : recorderAudioAsBlob.type;
          audioElementSource.type = BlobType
        }

        audioElement.load();
    };

    reader.readAsDataURL(recorderAudioAsBlob);
  }

  const change_active_page = val => {
    active_page_id.value = val;
  }

  const update_sender = () => {
    sender.value = generate_random_string(15);
  }

  const generate_random_string = length => {
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charLength = chars.length;
    var result = '';
    for ( var i = 0; i < length; i++ ) {
      result += chars.charAt(Math.floor(Math.random() * charLength));
    }
    return result;
  }

  return {
    history,
    add_history,
    update_history,
    last_id,
    change_last_id,
    id_now,
    change_id_now,
    toggle_history_session,
    add_history_session,
    session_open_state,
    opened_session_id,
    active_page_id,
    change_active_page,
    sender,
    update_sender
  }
})
