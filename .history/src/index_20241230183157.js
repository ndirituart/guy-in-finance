// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';

// Import the components for each page
import HelloWorld from '../components/HelloWorld.vue';
import MyExpensesPage from '../components/MyExpensesPage.vue';
import LoansPage from '../components/LoansPage.vue';
import Savings from '../components/Savings.vue';
import Relations from '../components/Relations.vue';
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
        next('/my-expenses'); // Redirect to MyExpensesPage if authenticated
      } else {
        next('/login'); // Redirect to login if not authenticated
      }
    }
  },
  { path: '/my-expenses', name: 'MyExpensesPage', component: MyExpensesPage, meta: { requiresAuth: true } },
  { path: '/LoansPage', name: 'LoansPage', component: LoansPage, meta: { requiresAuth: true } },
  { path: '/savings', name: 'Savings', component: Savings, meta: { requiresAuth: true } },
  { path: '/Relations', name: 'Relations', component: Relations, meta: { requiresAuth: true } },
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
