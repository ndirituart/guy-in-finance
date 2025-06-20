import { createApp } from 'vue'; // Import createApp from Vue 3
import App from './App.vue';
import router from './router'; // Assuming your router/index.js is correctly configured

const app = createApp(App); // Create a Vue 3 app instance

app.use(router); // Tell the app to use Vue Router

app.mount('#app'); // Mount the entire application to the #app div
