// main.js
import { createApp } from 'vue';
import App from './App.vue';
//for global functionalityof AccountBalance on different pages
import { createPinia } from 'pinia';
import router from '../router';

// FontAwesome setup
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Import specific icons (you can add more as needed)
import { faUser, faHome, faArrowRight } from '@fortawesome/free-solid-svg-icons';

library.add(faUser, faHome, faArrowRight); // add icons to the library

const app = createApp(App);
//for global functionality
const pinia = createPinia();

app.component('font-awesome-icon', FontAwesomeIcon); // register globally
app.use(router);
app.use(pinia); // use Pinia for global state management
app.mount('#app');
