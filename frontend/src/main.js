import "./assets/main.css";

import { createApp, markRaw } from "vue";
import { createPinia } from "pinia";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import App from "./App.vue";
import router from "./router";

const app = createApp(App);

const options = {
  transition: "Vue-Toastification__bounce",
  maxToasts: 20,
  newestOnTop: true,
  position: "top-center",
  timeout: 2000,
};

const pinia = createPinia();

pinia.use(({ store }) => {
  store.router = markRaw(router);
});

app.use(pinia);
app.use(router);
app.use(Toast, options);

app.mount("#app");
