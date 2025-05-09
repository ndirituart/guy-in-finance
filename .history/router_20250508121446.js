import { createRouter, createWebHistory } from "vue-router";


const routes = [
    {
        path: '/',
        component: () => import('.e'),
        children: [
            {
                path: '/',
                component: () => import('../components/HomePage.vue')
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