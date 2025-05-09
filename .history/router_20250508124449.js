import { createRouter, createWebHistory } from "vue-router";
import Router from 'vue-router';
Vue.use(Router);

export default new Router({
    routes: [
      {
        path: '/home',
        name: 'HomePage',
        component: HomePage
      }
    ]
  })
