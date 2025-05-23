<template>
   <Navbar />
   <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-indigo-950 flex items-center justify-center">
     <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 shadow-2xl w-full max-w-md">
       
       <div class="flex flex-col items-center">
         <h2 class="text-2xl font-bold text-white mt-4">{{ loggedUser.name }}</h2>
         <p class="text-gray-300 mb-6">{{ loggedUser.email }}</p>
       </div>
 
       <div class="space-y-4">
         <div class="bg-white/5 p-4 rounded-xl">
           <h3 class="text-sm text-gray-400">Nome</h3>
           <p class="text-white font-medium">{{ loggedUser.name }}</p>
         </div>
         <div class="bg-white/5 p-4 rounded-xl">
           <h3 class="text-sm text-gray-400">E-mail</h3>
           <p class="text-white font-medium">{{ loggedUser.email }}</p>
         </div>
       </div>
 
       <div class="flex gap-4 mt-8">
         <button @click="showModal = true" class="w-full bg-sky-500/50 hover:bg-sky-600 text-white font-semibold py-2 rounded-xl transition duration-300">
           Editar Perfil
         </button>
       </div>
     </div>
 
     <!-- Modal -->
     <div v-if="showModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
       <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 w-full max-w-md shadow-2xl">
         <h2 class="text-2xl font-semibold text-white mb-4">Editar Perfil</h2>
 
         <form @submit.prevent="updateProfile" class="space-y-4">
           <div>
             <label class="block mb-1 text-gray-300">Nome</label>
             <input v-model="loggedUser.name"
               type="text"
               class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
               required
             />
           </div>
 
           <div>
             <label class="block mb-1 text-gray-300">Email</label>
             <input v-model="loggedUser.email"
               type="email"
               class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
               required
             />
           </div>
 
           <div class="flex justify-end gap-4">
             <button type="button" @click="showModal = false" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl">
               Cancelar
             </button>
 
             <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl">
               Salvar
             </button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </template>
 
 
 <script setup>
import { ref, onMounted } from 'vue';
import Navbar from './Navbar.vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const showModal = ref(false);
const token = localStorage.getItem('token');
const loggedUser = ref({});

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
      alert('Erro: Não foi possível capturar dados do usuário');
      console.error('Erro ao buscar usuário logado:', error);
   }
};
 
// Atualizar perfil
const updateProfile = async () => {
   try {
      const res = await fetch(`http://127.0.0.1:8000/api/edit-user/${loggedUser.value.id}`, {
         method: 'PATCH',
         headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
         },
         body: JSON.stringify({
            name: loggedUser.value.name,
            email: loggedUser.value.email,
         }),
      });

      if (!res.ok) throw new Error('Erro ao atualizar perfil');

      const response = await res.json();
      console.log('Perfil atualizado com sucesso:', response);

      showModal.value = false;
      // implementar toast 
      alert('Perfil atualizado com sucesso!');
   } catch (error) {
      console.error('Erro ao atualizar perfil:', error);
      alert('Erro ao atualizar perfil');
   }
};

onMounted(() => {
   fetchLoggedUser();
});
</script>
 