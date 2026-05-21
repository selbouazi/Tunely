<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    instruments: Array,
    categories: Array,
});

const defaultFilters = () => ({
    category: 'Todos',
    type: 'Todos',
    precio_min: '',
    precio_max: '',
    sort: 'default',
});

const filters = ref(defaultFilters());
const appliedFilters = ref(defaultFilters());

const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const filteredInstruments = computed(() => {
    let result = props.instruments;
    const f = appliedFilters.value;

    if (f.category !== 'Todos') {
        result = result.filter(v => v.category && v.category.nombre === f.category);
    }
    if (f.type !== 'Todos') {
        result = result.filter(v => v.tipo === f.type);
    }
    if (f.precio_min !== '') {
        result = result.filter(v => v.precio >= Number(f.precio_min));
    }
    if (f.precio_max !== '') {
        result = result.filter(v => v.precio <= Number(f.precio_max));
    }
    if (f.sort === 'precio_asc') {
        result = [...result].sort((a, b) => a.precio - b.precio);
    } else if (f.sort === 'precio_desc') {
        result = [...result].sort((a, b) => b.precio - a.precio);
    } else if (f.sort === 'nombre_asc') {
        result = [...result].sort((a, b) => (a.marca + a.modelo).localeCompare(b.marca + b.modelo));
    } else if (f.sort === 'nombre_desc') {
        result = [...result].sort((a, b) => (b.marca + b.modelo).localeCompare(a.marca + a.modelo));
    }

    return result;
});

const applyFilters = () => {
    appliedFilters.value = { ...filters.value };
};

const clearFilters = () => {
    filters.value = defaultFilters();
    appliedFilters.value = defaultFilters();
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

        <section class="bg-[#FEFDDF] py-4 px-4 border-b border-[#1a1a1a]/10">
            <div class="max-w-7xl mx-auto flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-[#1a1a1a]/70 mb-1">Categoría</label>
                    <select v-model="filters.category" class="text-sm rounded border-gray-300 shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24]">
                        <option value="Todos">Todas</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.nombre">{{ cat.nombre }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#1a1a1a]/70 mb-1">Tipo</label>
                    <select v-model="filters.type" class="text-sm rounded border-gray-300 shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24]">
                        <option value="Todos">Todos</option>
                        <option value="nuevo">Nuevo</option>
                        <option value="usado">Usado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#1a1a1a]/70 mb-1">Precio mín</label>
                    <input type="number" v-model="filters.precio_min" placeholder="0" class="text-sm rounded border-gray-300 shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24] w-20">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#1a1a1a]/70 mb-1">Precio máx</label>
                    <input type="number" v-model="filters.precio_max" placeholder="9999" class="text-sm rounded border-gray-300 shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24] w-20">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#1a1a1a]/70 mb-1">Ordenar</label>
                    <select v-model="filters.sort" class="text-sm rounded border-gray-300 shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24]">
                        <option value="default">Por defecto</option>
                        <option value="precio_asc">Precio: menor a mayor</option>
                        <option value="precio_desc">Precio: mayor a menor</option>
                        <option value="nombre_asc">Nombre A-Z</option>
                        <option value="nombre_desc">Nombre Z-A</option>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <button @click="applyFilters" class="bg-[#E87F24] text-white px-4 py-1.5 text-sm font-bold hover:bg-[#FFC81E] transition">Filtrar</button>
                    <button @click="clearFilters" class="border border-[#E87F24] text-[#E87F24] px-4 py-1.5 text-sm font-bold hover:bg-[#E87F24] hover:text-white transition">Limpiar</button>
                </div>
            </div>
        </section>

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="v in filteredInstruments" :key="v.id" @click="router.visit('/catalogo/' + v.id)" class="bg-white p-4 rounded shadow cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
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
                                <button @click.stop="addToCart(v)" class="bg-[#E87F24] text-white px-4 py-1 text-sm hover:bg-[#FFC81E]">Añadir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>