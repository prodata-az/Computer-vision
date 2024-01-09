import { defineNuxtConfig } from "nuxt/config";
import { resolve } from 'path';

// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  // ssr: false,
  // routeRules: {
  //   '/admin/dashboard/main_kpi': { ssr: false },
  // },
  // vite: {
  //   server: {
  //     hmr: {
  //       protocol: "ws",
  //       clientPort: 82,
  //       path: "hmr/"
  //     },
  //   },
  // },

  runtimeConfig: {
    public: {
      backendUrl: "https://test-cv.prodata.az:8001",
      frontendUrl: "https://test-cv.prodata.az",
      domain: ''
    },
  },

  plugins: [
    { src: '~/plugins/bootstrap.client', mode: 'client' },
    { src: '~/plugins/laravel-echo.client', mode: 'client'}
  ],

  modules: [
    [
      '@pinia/nuxt',
      {
        autoImports: ['defineStore', 'acceptHMRUpdate'],
      }
    ]
  ],

  imports: {
    dirs: ["./utils", "stores"],
  },

  devtools: {
    enabled: false,
  },
});
