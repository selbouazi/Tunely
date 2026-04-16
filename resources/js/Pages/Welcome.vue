<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    instruments: Array,
});

const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const addToCart = (instrument) => {
    window.dispatchEvent(new CustomEvent('add-to-cart', { detail: instrument }));
};
</script>

<template>
    <AppLayout>
        <Head title="Inicio - Tunely" />

        <section class="bg-[#FEFDDF] py-16 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-4 text-[#1a1a1a]">Tu tienda de instrumentos musicales</h1>
                        <p class="text-[#1a1a1a]/70 mb-6">Instrumentos nuevos y de segunda mano</p>
                        <Link href="/catalogo" class="inline-block bg-[#E87F24] text-white px-6 py-2 font-bold hover:bg-[#FFC81E]">Ver Catálogo</Link>
                    </div>
                    <div>
                        <img src="https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=600" alt="Instrumento" class="w-full rounded">
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold mb-8 text-[#1a1a1a]">Instrumentos Destacados</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="v in instruments" :key="v.id" class="bg-[#FEFDDF] p-4 rounded shadow">
                        <img :src="v.imagen || defaultImage" :alt="v.marca + ' ' + v.modelo" class="w-full rounded mb-4">
                        <p class="text-[#1a1a1a]/70 text-sm">{{ v.marca }} {{ v.modelo }}</p>
                        <p class="text-lg font-bold text-[#1a1a1a]">{{ v.tipo === 'usado' ? 'Usado' : 'Nuevo' }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="font-bold text-xl text-[#E87F24]">{{ v.precio }}€</p>
                                <p v-if="v.precio_original" class="text-[#1a1a1a]/50 text-sm line-through">{{ v.precio_original }}€</p>
                                <p class="text-xs text-[#1a1a1a]/50">IVA incluido</p>
                            </div>
                            <button @click="addToCart(v)" class="bg-[#FFC81E] text-[#1a1a1a] px-3 py-1 text-sm hover:bg-[#E87F24]">Añadir</button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-center">
                    <Link href="/catalogo" class="text-[#E87F24] underline">Ver todos los instrumentos</Link>
                </div>
            </div>
        </section>

        <section class="bg-[#73A5CA] py-12 px-4">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-2xl font-bold mb-6 text-[#1a1a1a]">¿Tienes instrumentos para vender?</h2>
                <p class="text-[#1a1a1a]/80 mb-6">Te ofrecemos la mejor valoración</p>
                <Link href="/contacto" class="inline-block bg-[#E87F24] text-white px-6 py-2 font-bold hover:bg-[#FFC81E]">Contacto</Link>
            </div>
        </section>
    </AppLayout>
</template>