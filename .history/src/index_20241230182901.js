// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';

// Import the components for each page
import HelloWorld from '../components/HelloWorld.vue';
import MyExpenses from '../components/MyExpenses.vue';
import Loans from '../components/Loans.vue';
import Savings from '../components/Savings.vue';
import Kin from '../components/Kin.vue';
import LoginPage from '../components/LoginPage.vue';
import RegisterPage from '../components/RegisterPage.vue';

// Function to check if the user is authenticated
function isAuthenticated() {
  // Replace this with your actual authentication check (e.g., token verification)
  return !!localStorage.getItem('authToken');
}

// Define routes
const routes = [
  { 
    path: '/', 
    name: 'HelloWorld', 
    component: HelloWorld, 
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next('/my-expenses'); // Redirect to MyExpenses if authenticated
      } else {
        next('/login'); // Redirect to login if not authenticated
      }
    }
  },
  { path: '/my-expenses', name: 'MyExpenses', component: MyExpenses, meta: { requiresAuth: true } },
  { path: '/loans', name: 'Loans', component: Loans, meta: { requiresAuth: true } },
  { path: '/savings', name: 'Savings', component: Savings, meta: { requiresAuth: true } },
  { path: '/kin', name: 'Kin', component: Kin, meta: { requiresAuth: true } },
  { path: '/login', name: 'LoginPage', component: LoginPage },
  { path: '/register', name: 'RegisterPage', component: RegisterPage },
  { path: '/:catchAll(.*)', redirect: '/' } // Catch-all for undefined routes
];

// Create router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});

// Global Navigation Guard
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated()) {
    next('/login'); // Redirect to login if route requires auth and user is not authenticated
  } else {
    next(); // Allow access
  }
});

export default router;
