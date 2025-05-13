import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/',
    name: 'HelloWorld',
    component: () => import('../components/HelloWorld.vue')  // Lazy load component
  },
  {
    path: '/home',
    name: 'HomePage',
    component: () => import('../components/HomePage.vue')  // Lazy load component
  }
];

// Create and export the router instance
const router = createRouter({
  history: createWebHistory(),  // Use HTML5 history mode
  routes
});

export default router;
