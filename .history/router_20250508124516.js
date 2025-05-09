import { createRouter, createWebHistory } from "vue-router";
import Router from 'vue-router';
Vue.use(Router);

export default new Router({
    path: '/',
    component: () => import('../components/App.vue'),
    children: [
        {
            path: '/',
            name: 'HelloWorld',
            // This is the path to the component that will be loaded when the user navigates to the root path
            component: () => import('../guy-in-finance/src/components/HelloWorld.vue')
        },
        {
            path: '/home',
            name: 'HomePage',
            component: () => import('../components/HomePage.vue')
          }
          
    ]
}, );
;