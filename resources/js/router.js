import { createRouter, createWebHistory } from 'vue-router';

import Login from './pages/Login.vue'; 
import Home from './pages/Home.vue';
import MinhaConta from './pages/MinhaConta.vue';
import Cadastro from './pages/Cadastro.vue';

const routes = [
    { path: '/', name: 'login', component: Login },
    { path: '/cadastro', name: 'cadastro', component: Cadastro},
    { path: '/home', name: 'home', component: Home },
    { path: '/minha-conta', name: 'minha-conta', component: MinhaConta},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


export default router;
