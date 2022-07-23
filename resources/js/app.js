import './bootstrap';
import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.css'
import { createPinia } from 'pinia';
import router from './router'

// import the root component App from a single-file component.
import App from './components/app.vue'


const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount("#app")