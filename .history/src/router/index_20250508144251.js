// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import HelloWorld from '../components/HelloWorld.vue';
import HomePage from '../components/HomePage.vue';
import


const routes = [
  {
    path: '/',
    name: 'HelloWorld',
    component: HelloWorld
  },
  {
    path: '/home',
    name: 'HomePage',
    component: HomePage
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
