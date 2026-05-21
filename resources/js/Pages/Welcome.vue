<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductCarousel from '@/Components/ProductCarousel.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    instruments: Array,
    carouselItems: Array,
});

const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const addToCart = (instrument) => {
    window.dispatchEvent(new CustomEvent('add-to-cart', { detail: instrument }));
};
</script>

<template>
    <AppLayout>
        <Head title="Inicio - Tunely" />

        <section v-if="carouselItems && carouselItems.length > 0">
            <ProductCarousel :items="carouselItems" />
        </section>
        <section v-else class="bg-[#1a1a1a] py-16 px-4">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-4 text-white">Tu tienda de instrumentos musicales</h1>
                <p class="text-white/70 mb-6">Instrumentos nuevos y de segunda mano</p>
                <Link href="/catalogo" class="inline-block bg-[#E87F24] text-white px-6 py-2 font-bold hover:bg-[#FFC81E]">Ver Catálogo</Link>
            </div>
        </section>

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold mb-8 text-[#1a1a1a]">Instrumentos Destacados</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="v in instruments" :key="v.id" @click="router.visit('/catalogo/' + v.id)" class="bg-white p-4 rounded shadow cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-1">
                        <img :src="v.imagen || defaultImage" :alt="v.marca + ' ' + v.modelo" class="w-full rounded mb-4">
                        <p class="text-[#1a1a1a]/70 text-sm">{{ v.marca }} {{ v.modelo }}</p>
                        <p class="text-lg font-bold text-[#1a1a1a]">{{ v.tipo === 'usado' ? 'Usado' : 'Nuevo' }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="font-bold text-xl text-[#E87F24]">{{ v.precio }}€</p>
                                <p v-if="v.precio_original" class="text-[#1a1a1a]/50 text-sm line-through">{{ v.precio_original }}€</p>
                            </div>
                            <button @click.stop="addToCart(v)" class="bg-[#FFC81E] text-[#1a1a1a] px-3 py-1 text-sm hover:bg-[#E87F24]">Añadir</button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-center">
                    <Link href="/catalogo" class="text-[#E87F24] underline">Ver todos los instrumentos</Link>
                </div>
            </div>
        </section>

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold mb-6 text-center text-[#1a1a1a]">Encuéntranos</h2>
                <div class="w-full max-w-3xl mx-auto aspect-video rounded shadow overflow-hidden">
                    <iframe
                        src="https://www.openstreetmap.org/export/embed.html?bbox=2.1600%2C41.3800%2C2.1800%2C41.3900&amp;layer=mapnik&amp;marker=41.3850%2C2.1700"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Ubicación de Tunely"
                    ></iframe>
                </div>
                <p class="text-center text-sm text-[#1a1a1a]/70 mt-4">C/ Gran Via, 123, 08014 Barcelona</p>
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