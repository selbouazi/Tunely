<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    instrument: Object,
    userRating: Object,
    canRate: Boolean,
});

const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const form = useForm({
    rating: 5,
    comment: '',
});

const submitRating = () => {
    form.post(route('instrumento.rating.store', props.instrument.id));
};

const avgRating = computed(() => {
    const ratings = props.instrument.ratings;
    if (!ratings || ratings.length === 0) return null;
    const sum = ratings.reduce((acc, r) => acc + r.rating, 0);
    return (sum / ratings.length).toFixed(1);
});

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
                        <img :src="instrument.imagen || defaultImage" :alt="instrument.marca + ' ' + instrument.modelo" class="w-full rounded shadow">
                    </div>
                    <div>
                        <p class="text-[#1a1a1a]/70 text-sm">{{ instrument.category?.nombre }}</p>
                        <h1 class="text-3xl font-bold mb-2 text-[#1a1a1a]">{{ instrument.marca }} {{ instrument.modelo }}</h1>
                        <p class="text-lg font-bold mb-4 text-[#1a1a1a]">
                            {{ instrument.tipo === 'usado' ? 'Usado' : 'Nuevo' }}
                        </p>

                        <div class="flex items-center gap-2 mb-2">
                            <span v-if="avgRating" class="text-[#E87F24] font-bold text-lg">{{ avgRating }}</span>
                            <span v-if="avgRating" class="text-yellow-500 text-lg">★</span>
                            <span v-if="instrument.ratings?.length" class="text-[#1a1a1a]/50 text-sm">({{ instrument.ratings.length }} opiniones)</span>
                        </div>

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

                        <button v-if="instrument.stock > 0" @click="addToCart(instrument)" class="bg-[#E87F24] text-white px-8 py-3 font-bold rounded hover:bg-[#FFC81E]">
                            Añadir al carrito
                        </button>
                        <button v-else disabled class="bg-gray-400 text-white px-8 py-3 font-bold rounded cursor-not-allowed">
                            Agotado
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section v-if="canRate && !userRating" class="bg-[#FEFDDF] py-8 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Valorar este producto</h2>
                <p class="text-[#1a1a1a]/70 text-sm mb-4">Has comprado este producto. ¡Comparte tu opinión!</p>
                <form @submit.prevent="submitRating" class="max-w-md space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Puntuación</label>
                        <div class="flex gap-1">
                            <button v-for="n in 5" :key="n" type="button" @click="form.rating = n"
                                class="text-2xl px-1"
                                :class="n <= form.rating ? 'text-yellow-500' : 'text-gray-300'"
                            >★</button>
                        </div>
                        <p v-if="form.errors.rating" class="text-red-600 text-xs mt-1">{{ form.errors.rating }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Comentario (opcional)</label>
                        <textarea v-model="form.comment" rows="3" class="w-full border border-gray-300 px-3 py-2 rounded" placeholder="Tu opinión..."></textarea>
                    </div>
                    <button type="submit" :disabled="form.processing" class="bg-[#E87F24] text-white px-6 py-2 rounded font-bold hover:bg-[#FFC81E] disabled:bg-gray-400">
                        {{ form.processing ? 'Enviando...' : 'Enviar valoración' }}
                    </button>
                </form>
            </div>
        </section>

        <section class="bg-white py-8 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-xl font-bold text-[#1a1a1a] mb-6">Opiniones de clientes</h2>
                <div v-if="!instrument.ratings?.length" class="text-[#1a1a1a]/50 text-sm">
                    No hay opiniones todavía. ¡Sé el primero en valorar!
                </div>
                <div v-else class="space-y-4 max-w-2xl">
                    <div v-for="r in instrument.ratings" :key="r.id" class="border border-gray-200 p-4 rounded">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-medium text-[#1a1a1a] text-sm">{{ r.user?.name }}</span>
                            <span class="text-yellow-500">{{ '★'.repeat(r.rating) }}{{ '☆'.repeat(5 - r.rating) }}</span>
                        </div>
                        <p v-if="r.comment" class="text-[#1a1a1a]/70 text-sm">{{ r.comment }}</p>
                        <p class="text-[#1a1a1a]/40 text-xs mt-1">{{ new Date(r.created_at).toLocaleDateString('es-ES') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
