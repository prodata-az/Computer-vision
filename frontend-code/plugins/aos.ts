import AOS from 'aos';
import 'aos/dist/aos.css';

export default defineNuxtPlugin((nuxtApp) => {
  if (typeof window !== 'undefined') {
    nuxtApp.AOS = AOS.init({
      once: false,
      disable: 'phone',
      offset: 100,
      duration: 650,
      easing: 'ease-in-out',
    });
  }
});
