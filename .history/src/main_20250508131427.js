// main.js
import { createApp } from 'vue'
import App from './App.vue'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'
//i want icons so i import all solid icons

library.add(fas)

const app = createApp(App)
app.component('font-awesome-icon', FontAwesomeIcon)
app.mount('#app')

// main.js or main.ts
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // make sure you import your router

const app = createApp(App);

app.use(router); // this is critical!

app.mount('#app');
