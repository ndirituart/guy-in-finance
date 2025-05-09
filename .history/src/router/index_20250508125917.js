import Vue from 'vue';
import Router from 'vue-router';
import HelloWorld from '../components/HelloWorld.vue';
import HomePage from '../components/HomePage.vue'; // Ensure this path is correct

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: () => import('../components/HelloWorld.vue')
    },
    {
      path: '/home',
      name: 'HomePage',
      component: () => import('../components/HomePage.vue')
    }
  ]
});
