<template>
  <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-indigo-900 flex items-center justify-center">
    <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-10 shadow-2xl w-full max-w-md">
      <h2 class="text-3xl font-bold text-white text-center mb-8">Login - Amar</h2>

      <form @submit.prevent="handleLogin">
        <div class="mb-5">
          <label class="block text-sm font-medium text-white mb-1">E-mail</label>
          <input type="email" v-model="email"
            class="w-full px-4 py-2 rounded-xl bg-white/20 border border-white/30 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="email@exemplo.com" required />
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-white mb-1">Senha</label>
          <input type="password" v-model="password"
            class="w-full px-4 py-2 rounded-xl bg-white/20 border border-white/30 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="••••••••" required />
        </div>

        <button type="submit"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-xl transition duration-300">
          Entrar
        </button>
      </form>

      <p class="text-sm text-gray-300 mt-6 text-center">
        Não tem uma conta? <a href="#" class="text-indigo-400 hover:underline">Cadastre-se</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const auth = useAuthStore();

const email = ref('');
const password = ref('');
const error = ref('');

const handleLogin = async () => {
  try {
    await auth.login(email.value, password.value);
    router.push('/home');
  } catch (err) {
    error.value = err;
  }
};
</script>
