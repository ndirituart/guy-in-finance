import { createRouter, createWebHistory } from "vue-router";


const routes = [
    {
        path: '/',
        component: () => import('../components/HelloWorld.vue'),
        children: [
            {
                path: '/',
                component: () => import('../components/HomePage.vue')
            },
            
           
        ]
    }, 
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
export default router;