<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, inject } from 'vue';

const props = defineProps({
    instruments: Array,
    categories: Array,
});

const page = usePage();
const selectedCategory = ref('Todos');

const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const filteredInstruments = (instruments) => {
    if (selectedCategory.value === 'Todos') return instruments;
    return instruments.filter(v => v.category && v.category.nombre === selectedCategory.value);
};

const addToCart = (instrument) => {
    window.dispatchEvent(new CustomEvent('add-to-cart', { detail: instrument }));
};
</script>

<template>
    <AppLayout>
        <Head title="Catálogo - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold mb-4 text-[#1a1a1a]">Catálogo</h1>
                <p class="text-[#1a1a1a]/70">Encuentra el instrumento perfecto</p>
            </div>
        </section>

        <section class="bg-white py-4 px-4">
            <div class="max-w-7xl mx-auto flex flex-wrap gap-2">
                <button 
                    v-for="cat in categories" 
                    :key="cat.id" 
                    class="px-3 py-1 text-sm border border-[#E87F24] text-[#E87F24] hover:bg-[#E87F24] hover:text-white transition"
                    :class="{ 'bg-[#E87F24] text-white': selectedCategory === cat.nombre }"
                    @click="selectedCategory = cat.nombre"
                >
                    {{ cat.nombre }}
                </button>
                <button 
                    class="px-3 py-1 text-sm border border-[#E87F24] text-[#E87F24] hover:bg-[#E87F24] hover:text-white transition"
                    :class="{ 'bg-[#E87F24] text-white': selectedCategory === 'Todos' }"
                    @click="selectedCategory = 'Todos'"
                >
                    Todos
                </button>
            </div>
        </section>

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="v in filteredInstruments(instruments)" :key="v.id" class="bg-white p-4 rounded shadow">
                        <img :src="v.imagen || defaultImage" :alt="v.marca + ' ' + v.modelo" class="w-full rounded mb-4">
                        <p class="text-[#1a1a1a]/70 text-sm">{{ v.marca }} {{ v.modelo }}</p>
                        <p class="text-lg font-bold text-[#1a1a1a]">{{ v.tipo === 'usado' ? 'Usado' : 'Nuevo' }}</p>
                        <p class="text-[#1a1a1a]/50 text-sm">Stock: {{ v.stock }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <div>
                                <span class="text-xl font-bold text-[#E87F24]">{{ v.precio }}€</span>
                                <span v-if="v.precio_original" class="text-[#1a1a1a]/50 text-sm ml-2 line-through">{{ v.precio_original }}€</span>
                            </div>
                            <div class="flex gap-2">
                                <Link :href="'/catalogo/' + v.id" class="bg-[#FFC81E] text-[#1a1a1a] px-4 py-1 text-sm hover:bg-[#E87F24]">Ver</Link>
                                <button @click="addToCart(v)" class="bg-[#E87F24] text-white px-4 py-1 text-sm hover:bg-[#FFC81E]">Añadir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>