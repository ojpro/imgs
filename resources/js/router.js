import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/pages/Home.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/upload",
        name: "Upload",
        component: () => import('@/views/pages/Upload.vue'),
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
export default router;
