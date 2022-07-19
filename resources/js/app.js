import './bootstrap';
import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.css'

// import the root component App from a single-file component.
import App from './components/app.vue'


const app = createApp(App).mount("#app")