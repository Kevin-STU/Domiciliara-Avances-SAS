
import './index.css'
import { createApp } from 'vue'
import store from './store'
import Router from './router'
import App from './App.vue'

createApp(App).use(Router).use(store).mount('#app')
