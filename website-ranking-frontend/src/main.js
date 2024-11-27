import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router';
import { createPinia } from 'pinia';
import Toast, { POSITION } from 'vue-toastification';
import '@fortawesome/fontawesome-free/css/all.css';
import 'vue-toastification/dist/index.css';

const app = createApp(App);
const pinia = createPinia();

app.use(Toast, {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: true,
    closeButton: "button",
    icon: "fas fa-rocket",
    rtl: false
});


app.use(pinia);
app.use(router);


app.mount('#app');
