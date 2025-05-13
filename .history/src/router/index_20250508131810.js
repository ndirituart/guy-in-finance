import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: () => import('../components/HelloWorld.vue')  // Dynamic import (no need to import at top)
    },
    {
      path: '/home',
      name: 'HomePage',
      component: () => import('../components/HomePage.vue')  // Dynamic import (no need to import at top)
    }
  ]
});
