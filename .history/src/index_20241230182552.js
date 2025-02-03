// // src/router.js
// import { createRouter, createWebHistory } from 'vue-router';
// import ExplorePage from './components/ExplorePage.vue';
// import HelloWorld from './components/HelloWorld.vue';
// import LoginPage from './components/LoginPage.vue';
// import SignUp from './components/SignUp.vue';
// import UserCart from './components/UserCart.vue';// Correct import from 'vue-router'


// const routes = [
//   { path: '/', name: 'HelloWorld', component: HelloWorld},  // Redirect root path to login
//   { path: '/explorepage', name: 'ExplorePage', component: ExplorePage, props: true },
//   { path: '/login', name: 'LoginPage', component: LoginPage, props: true },
//   { path: '/signup', name: 'SignUp', component: SignUp, props: true },
//   { path: '/usercart', name: 'UserCart', component: UserCart, props: true },
//   { path: '/:catchAll(.*)', redirect: '/' }  // Catch-all for undefined routes
// ];

// const router = createRouter({
//   history: createWebHistory(),
//   routes
// });

// export default router;

import { createRouter, createWebHistory } from 'vue-router';
import ExplorePage from './components/ExplorePage.vue';
import HelloWorld from './components/HelloWorld.vue';
import LoginPage from './components/LoginPage.vue';
import SignUp from './components/SignUp.vue';
import UserCart from './components/UserCart.vue'; // Corrected import for UserCart
import ShoppingPage from './components/ShoppingPage.vue';
import ClearCart from './components/ClearCart.vue';

// Function to check if the user is authenticated
function isAuthenticated() {
  // Replace this with your actual authentication check
  return !!localStorage.getItem('authToken');
}

const routes = [

  { path: '/shoppingpage', name: 'ShoppingPage', component: ShoppingPage, props: true },  // Redirect root path to login
{ path: '/explorepage', name: 'ExplorePage', component: ExplorePage, props: true },
  { path: '/', name: 'HelloWorld', component: HelloWorld, 
    beforeEnter: (to, from, next) => {
    if (isAuthenticated()) {
      next('/usercart');  // Redirect to explore page if authenticated
    } else {
      next('/login');  // Redirect to login page if not authenticated
    }
  }},
  { path: '/explorepage', name: 'ExplorePage', component: ExplorePage, props: true,},
  { path: '/login', name: 'LoginPage', component: LoginPage, props: true },
  { path: '/signup', name: 'SignUp', component: SignUp, props: true },
 { path: '/shoppingpage', name: 'ShoppingPage', component: ShoppingPage, props: true },
  //I will add the authentication later so that you have to login to use your cart
  // meta: { requiresAuth: true }
  { path: '/usercart', name: 'UserCart', component: UserCart, props: true, },
  {path: '/clearcart', name:'ClearCart', component:ClearCart, props: true },
  { path: '/:catchAll(.*)', redirect: '/' }  // Catch-all for undefined routes
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Global Navigation Guard
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated()) {
    next('/login');  // Redirect to login if route requires auth and user is not authenticated
  } else {
    next();  // Allow access
  }
});

export default router;
