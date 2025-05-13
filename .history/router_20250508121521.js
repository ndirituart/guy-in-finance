import { createRouter, createWebHistory } from "vue-router";


const routes = [
    {
        path: '/',
        component: () => import('../components/App.vue'),
        children: [
            {
                path: '/',
                component: () => import('../guy-in-finance/src')
            },
            // {
            //     path: '/about',
            //     component: () => import('../components/AboutPage.vue')
            // },
           
        ]
    }, 
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
export default router;