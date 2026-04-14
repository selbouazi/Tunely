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
        <Head title="Inicio - Soundly" />

        <section class="bg-black py-16 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-4">Tu tienda de instrumentos musicales</h1>
                        <p class="text-gray-400 mb-6">Instrumentos nuevos y de segunda mano</p>
                        <Link href="/catalogo" class="inline-block bg-[#6AECE1] text-black px-6 py-2 font-bold hover:bg-[#26CCC2]">Ver Catálogo</Link>
                    </div>
                    <div>
                        <img src="https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=600" alt="Instrumento" class="w-full rounded">
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-gray-900 py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold mb-8">Instrumentos Destacados</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="v in instruments" :key="v.id" class="bg-black p-4">
                        <img :src="v.imagen || defaultImage" :alt="v.marca + ' ' + v.modelo" class="w-full rounded mb-4">
                        <p class="text-gray-400 text-sm">{{ v.marca }} {{ v.modelo }}</p>
                        <p class="text-lg font-bold">{{ v.tipo === 'usado' ? 'Usado' : 'Nuevo' }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="font-bold text-xl">{{ v.precio }}€</p>
                                <p v-if="v.precio_original" class="text-gray-500 text-sm line-through">{{ v.precio_original }}€</p>
                            </div>
                            <button @click="addToCart(v)" class="bg-[#6AECE1] text-black px-3 py-1 text-sm hover:bg-[#26CCC2]">Añadir</button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-center">
                    <Link href="/catalogo" class="text-[#6AECE1] underline">Ver todos los instrumentos</Link>
                </div>
            </div>
        </section>

        <section class="bg-black py-12 px-4">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-2xl font-bold mb-6">¿Tienes instrumentos para vender?</h2>
                <p class="text-gray-400 mb-6">Te ofrecemos la mejor valoración</p>
                <Link href="/contacto" class="inline-block bg-[#6AECE1] text-black px-6 py-2 font-bold hover:bg-[#26CCC2]">Contacto</Link>
            </div>
        </section>
    </AppLayout>
</template>