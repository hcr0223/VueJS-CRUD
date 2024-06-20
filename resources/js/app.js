import './bootstrap';
import { createApp } from 'vue';
import app from "./layouts/app.vue";
import vuetify from './vuetify';
import router from './router';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

createApp(app).use(vuetify).use(router).use(VueSweetalert2).mount("#app");