import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../api/axios';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter();

  function loadUser() {
    try {
      const item = localStorage.getItem('user');
      return item && item !== 'undefined' ? JSON.parse(item) : null;
    } catch (e) {
      console.error('Erro ao fazer parse do user no localStorage', e);
      localStorage.removeItem('user');
      return null;
    }
  }

  const user = ref(loadUser());
  const isAuthenticated = computed(() => !!user.value);

  async function login(email, password) {
    try {
      const response = await api.post('/login', { email, password });
      user.value = response.data.user;
      localStorage.setItem('user', JSON.stringify(user.value));
      router.push('/home');
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

  return { user, isAuthenticated, login, fetchUser };
});
