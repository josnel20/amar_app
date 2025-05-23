<template>
    <nav class="flex justify-between items-center bg-gray-800 p-4 shadow-md text-white">
        <div class="flex items-center space-x-6">
            <router-link to="/home" class="hover:text-cyan-300 font-medium cursor-pointer">Home</router-link>
            <router-link to="/minha-conta" class="hover:text-cyan-300 font-medium cursor-pointer">Perfil</router-link>
        </div>

        <div class="flex items-center space-x-4">
            <span class="text-gray-300 text-sm">Olá, {{ loggedUser.email || 'Usuário' }}</span>
            <button @click="logout" class="bg-red-600 hover:bg-red-700 transition-colors rounded-md px-4 py-1 text-sm font-semibold cursor-pointer">
                Sair
            </button>
        </div>
    </nav>
</template>
  
<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const token = localStorage.getItem('token');
const loggedUser = ref({});
import { useAuthStore } from '../stores/auth';
const auth = useAuthStore();
const userLogado = '';

//dados do user
const fetchLoggedUser = async () => {
    try {
        const res = await fetch('http://127.0.0.1:8000/api/me', {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        if (!res.ok) throw new Error('Erro ao buscar dados do usuário');

        const response = await res.json();

        loggedUser.value = response.user;
    } catch (error) {
        alert('Erro ao buscar dados do usuário');
        console.error('Erro ao buscar usuário logado:', error);
    }
};

const logout = async () => {
    try {
        const res = await fetch('http://127.0.0.1:8000/api/logout', {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
      
        if (!res.ok) {
            const errorData = await res.json();
            console.error('Erro no logout:', errorData);
            throw new Error(errorData.message || 'Erro ao fazer logout');
        }

        localStorage.removeItem('token');
        localStorage.removeItem('user');

        router.push('/');
    } catch (error) {
        console.error('Erro no logout:', error);
        alert('Erro ao fazer logout. Tente novamente.');
    }
};

onMounted(() => {
    fetchLoggedUser();
});
</script>
  