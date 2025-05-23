import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../api/axios';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter();

  const user = ref(JSON.parse(localStorage.getItem('user')) || null);

  const isAuthenticated = computed(() => !!user.value);

  async function login(email, password) {
    try {
      const response = await api.post('/login', { email, password });
      user.value = response.data.user;
      localStorage.setItem('user', JSON.stringify(user.value));
    } catch (error) {
      throw error.response?.data?.message || 'Erro no login';
    }
  }

  async function fetchUser() {
    try {
      const response = await api.get('/me');
      user.value = response.data;
      localStorage.setItem('user', JSON.stringify(user.value));
    } catch (error) {
      await logout();
    }
  }

  async function logout() {
    try {
      await api.post('/logout');
      
    } catch (error) {
      console.error('Erro no logout:', error);
    } finally {
      user.value = null;
      localStorage.removeItem('user');
      router.push('/');
    }
  }

  return { user, isAuthenticated, login, fetchUser, logout };
});
