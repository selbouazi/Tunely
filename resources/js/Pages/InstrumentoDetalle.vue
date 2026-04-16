<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    instrument: Object
});

const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const addToCart = (instrument) => {
    window.dispatchEvent(new CustomEvent('add-to-cart', { detail: instrument }));
};
</script>

<template>
    <AppLayout>
        <Head :title="`${instrument.marca} ${instrument.modelo} - Tunely`" />

        <section class="bg-[#FEFDDF] py-8 px-4">
            <div class="max-w-7xl mx-auto">
                <Link href="/catalogo" class="text-[#E87F24] hover:underline">← Volver al catálogo</Link>
            </div>
        </section>

        <section class="bg-white py-8 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <img 
                            :src="instrument.imagen || defaultImage" 
                            :alt="instrument.marca + ' ' + instrument.modelo" 
                            class="w-full rounded shadow"
                        >
                    </div>
                    <div>
                        <p class="text-[#1a1a1a]/70 text-sm">{{ instrument.category?.nombre }}</p>
                        <h1 class="text-3xl font-bold mb-2 text-[#1a1a1a]">{{ instrument.marca }} {{ instrument.modelo }}</h1>
                        <p class="text-lg font-bold mb-4 text-[#1a1a1a]">
                            {{ instrument.tipo === 'usado' ? 'Usado' : 'Nuevo' }}
                        </p>
                        
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-[#E87F24]">{{ instrument.precio }}€</span>
                            <span v-if="instrument.precio_original" class="text-[#1a1a1a]/50 text-xl ml-3 line-through">{{ instrument.precio_original }}€</span>
                            <span class="block text-sm text-[#1a1a1a]/50 mt-1">IVA incluido</span>
                        </div>

                        <div class="mb-6">
                            <p class="text-[#1a1a1a]/70">
                                <span class="font-bold">Stock:</span> 
                                <span :class="instrument.stock > 0 ? 'text-green-600' : 'text-red-600'">
                                    {{ instrument.stock > 0 ? `${instrument.stock} unidades` : 'Agotado' }}
                                </span>
                            </p>
                        </div>

                        <p v-if="instrument.descripcion" class="text-[#1a1a1a]/70 mb-6">{{ instrument.descripcion }}</p>

                        <button 
                            v-if="instrument.stock > 0"
                            @click="addToCart(instrument)" 
                            class="bg-[#E87F24] text-white px-8 py-3 font-bold rounded hover:bg-[#FFC81E]"
                        >
                            Añadir al carrito
                        </button>
                        <button 
                            v-else 
                            disabled 
                            class="bg-gray-400 text-white px-8 py-3 font-bold rounded cursor-not-allowed"
                        >
                            Agotado
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>