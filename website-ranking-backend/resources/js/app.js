import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import Toast, { POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import { createPinia } from 'pinia';


const app = createApp(App)
const pinia = createPinia();
app.use(router)
app.use(pinia)

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
    rtl: false
});

app.mount('#app')
