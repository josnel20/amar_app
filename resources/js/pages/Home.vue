<template>
    <Navbar />
    <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-indigo-950 p-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl text-white font-bold mb-8">Meus Produtos</h1>

            <div class="flex justify-end mb-6">
                <button @click="openModal"
                    class="bg-sky-500/50 hover:bg-sky-600 text-white px-6 py-2 rounded-xl transition cursor-pointer">
                    + Adicionar Produto
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="{{ product.title }}">
                <div v-for="(product, index) in products" :key="index" class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 shadow-lg">
                   
                    <div class="mb-4">
                        <img v-if="product.images.length" :src="product.images[0]"
                            class="w-full h-48 object-cover rounded-xl" alt="Imagem do Produto" />
                        <div v-else class="w-full h-48 bg-gray-700 rounded-xl flex items-center justify-center">
                            <span class="text-gray-400">Sem imagem</span>
                        </div>
                    </div>
                    
                    <h2 class="text-xl text-white font-bold">{{ product.title }}</h2>
                    <label class="text-sm text-gray-300" v-html="product.description"></label>

                    <div class="mt-4 space-y-1">
                        <p class="text-sm text-gray-300"><strong>Preço de Venda:</strong> RS {{ product.salePrice }}</p>
                        <p class="text-sm text-gray-300"><strong>Custo:</strong> RS {{ product.cost }}</p>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button @click="openEditModal(product)" class="bg-sky-500/50 hover:bg-sky-600 px-3 py-1 rounded-lg text-white cursor-pointer">
                            Editar
                          </button>
                          
                        <button @click="inativarProduto(product.id)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-xl cursor-pointer">
                            Inativar
                        </button>
                    </div>
                </div>
            </div>
        </div>

         <!-- Modal para adicionar produtos -->
        <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl p-8 w-full max-w-lg shadow-xl relative">

                <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl leading-none">
                    &times;
                </button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Adicionar Produto</h2>

                <form @submit.prevent="addProduct" class="space-y-4">

                    <div>
                        <label class="block mb-1 text-sm text-gray-700 font-medium">Título</label>
                        <input v-model="newProduct.title" type="text" required
                            class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none"/>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1 text-sm text-gray-700 font-medium">Preço de Venda (Kz)</label>
                            <input v-model="newProduct.salePrice" type="number" required
                                class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none"/>
                        </div>

                        <div class="w-1/2">
                            <label class="block mb-1 text-sm text-gray-700 font-medium">Custo (Kz)</label>
                            <input v-model="newProduct.cost" type="number" required
                                class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none"/>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-700 font-medium">Descrição</label>
                        <textarea v-model="newProduct.description" rows="3"
                            class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none resize-none"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-sky-600 hover:bg-sky-700 text-white px-4 py-3 rounded-xl transition">
                        Salvar Produto
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal para editar produtos -->
        <div v-if="isEditModalOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-xl">

                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Editar Produto</h2>

                <div class="space-y-4">
                    <input v-model="selectedProduct.title" type="text" placeholder="Título" class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none"/>
                    <textarea v-model="selectedProduct.description" placeholder="Descrição" class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none resize-none h-24"></textarea>
                    <input v-model="selectedProduct.salePrice" type="number" placeholder="Preço de Venda" class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none"/>
                    <input v-model="selectedProduct.cost" type="number" placeholder="Custo" class="w-full border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 p-3 rounded-xl outline-none"/>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="isEditModalOpen = false" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-xl transition">
                        Cancelar
                    </button>

                    <button @click="saveProductChanges" class="bg-sky-600 hover:bg-sky-700 text-white px-5 py-2 rounded-xl transition">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue';
import Navbar from './Navbar.vue';

const products = ref([]);
const showModal = ref(false);
const token = localStorage.getItem('token');
const selectedProduct = ref(null);
const isEditModalOpen = ref(false);
const openEditModal = (product) => {
  selectedProduct.value = { ...product };
  isEditModalOpen.value = true;
};

const newProduct = ref({
    title: '',
    images: [],
    salePrice: '',
    cost: '',
    description: '',
});

//Produto
const fetchProducts = async () => {
    try {
        const res = await fetch('http://127.0.0.1:8000/api/produtos', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            },
        });

        if (!res.ok) throw new Error('Erro ao buscar produtos');
        const response = await res.json();
        const produtosApi = response.data.data;

        products.value = produtosApi.map(data => ({
            id: data.id,
            title: data.titulo || 'Sem título',
            images: '',
            salePrice: data.preco_venda || 0,
            cost: data.custo || 0,
            description: data.descricao || '',
        }));

    } catch (error) {
        console.error(error);
        products.value = [];
    }
};
// adicionar produtos
const addProduct = async () => {
    try {
        const formData = new FormData();

        formData.append('titulo', newProduct.value.title);
        formData.append('descricao', newProduct.value.description);
        formData.append('preco_venda', newProduct.value.salePrice);
        formData.append('custo', newProduct.value.cost);

        await fetchPostProducts(formData);

        closeModal();
        fetchProducts();
        alert('Sucesso: Produto adicionado com sucesso');
    } catch (error) {
        alert('Erro: Não foi possível adicionar novo Produto');
        console.error(error);
    }
};
const fetchPostProducts = async (formData) => {
    try {
        const res = await fetch('http://127.0.0.1:8000/api/produtos/criar', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: formData,
        });

        if (!res.ok) {
            console.error('Erro bruto:', res);
            throw new Error(`Erro no POST`);
        }

        const response = await res.json();
        console.log('Resposta da API:', response);
    } catch (error) {
        console.error('Erro no POST:', error);
        throw new Error(error ?? 'Erro ao criar produto');
    }
};
// Inativar produto
const inativarProduto = async (id) => {
    try {
        const res = await fetch(`http://127.0.0.1:8000/api/produtos/inativar/${id}`, {
            method: 'PUT',
            headers: {
            Authorization: `Bearer ${token}`,
            },
        });

        if (!res.ok) throw new Error('Erro ao inativar produto');

        const response = await res.json();
        // implementar toast 
        fetchProducts();
        alert('Sucesso: Produto Desativado com sucesso');
    } catch (error) {
        console.error(error);
        alert('Erro: Não foi possível Desativado Produto');
    }
};
// Editar prodtuto
const saveProductChanges = async () => {
    try {
        const res = await fetch(`http://127.0.0.1:8000/api/produtos/edit/${selectedProduct.value.id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                titulo: selectedProduct.value.title,
                descricao: selectedProduct.value.description,
                preco_venda: selectedProduct.value.salePrice,
                custo: selectedProduct.value.cost,
            }),
        });

        if (!res.ok) throw new Error('Erro ao editar produto');

        const response = await res.json();
        console.log('Produto editado com sucesso:', response);
        // implementar toast 
        isEditModalOpen.value = false;
        fetchProducts();
        alert('Sucesso: Produto atualizado com sucesso');
    } catch (error) {
        console.error(error);
        alert('Erro: Não foi possível editar Produto');
    }
};

// imagens
const handleFileUpload = (event) => {
    this.newProduct.images = Array.from(event.target.files);
};

//modal
const openModal = () => {
    showModal.value = true;
};
const closeModal = () => {
    showModal.value = false;
    newProduct.value = {
        title: '',
        images: [],
        salePrice: '',
        cost: '',
        description: '',
    };
};

onMounted(() => {
    fetchProducts();
});

</script>

