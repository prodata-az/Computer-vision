import { defineNuxtPlugin } from '#app';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default defineNuxtPlugin(() => {
  window.Pusher = Pusher;
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '4f0dd7d73336125911c8',
    cluster: 'eu',
    forceTLS: true,
  });
});