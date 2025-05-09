import { createRouter, createWebHistory } from "vue-router";


const routes = [
    {
        path: '/',
        component: () => import('../components/App.vue'),
        children: [
            {
                path: '/',
                component: () => import('../guy-in-finance/src/components/HelloWorld.vue')
            },
            {
                path: '/home',
                name: 'HomePage',
                component: () => import('../components/HomePage.vue')
              }
              
        ]
    }, 
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
export default router;