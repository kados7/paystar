import './bootstrap';

import { createApp } from 'vue';
import HeaderComponent from './vueComponents/home/HeaderComponent.vue'
import router from './router.js';
import { createPinia } from 'pinia'
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap/dist/css/bootstrap.min.css'

const app=createApp();
const pinia = createPinia()

app.component('HeaderComponent',HeaderComponent);
app.use(pinia);
app.use(router);
app.mount('#app');
