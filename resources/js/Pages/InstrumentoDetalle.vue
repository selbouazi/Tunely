<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
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

const queryForm = useForm({
    name: usePage().props.auth?.user?.name || '',
    email: usePage().props.auth?.user?.email || '',
    message: '',
});

const submitRating = () => {
    form.post(route('instrumento.rating.store', props.instrument.id));
};

const submitQuery = () => {
    queryForm.post(route('instrumento.consulta', props.instrument.id));
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

const queryValid = computed(() => {
    return queryForm.name && queryForm.email && queryForm.message && queryForm.message.length <= 150;
});

const messageLength = computed(() => queryForm.message?.length || 0);
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

        <section class="bg-[#FEFDDF] py-8 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8">
                    <div v-if="canRate && !userRating">
                        <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Valorar este producto</h2>
                        <p class="text-[#1a1a1a]/70 text-sm mb-4">Has comprado este producto. ¡Comparte tu opinión!</p>
                        <form @submit.prevent="submitRating" class="space-y-4">
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

                    <div>
                        <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Consultar sobre este producto</h2>
                        <p class="text-[#1a1a1a]/70 text-sm mb-4">¿Tienes dudas? Escríbenos y te responderemos.</p>

                        <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                            {{ $page.props.flash.success }}
                        </div>

                        <form @submit.prevent="submitQuery" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Nombre *</label>
                                <input v-model="queryForm.name" type="text"
                                    class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-[#E87F24] focus:border-transparent"
                                    placeholder="Tu nombre">
                                <p v-if="queryForm.errors.name" class="text-red-600 text-xs mt-1">{{ queryForm.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Email *</label>
                                <input v-model="queryForm.email" type="email"
                                    class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-[#E87F24] focus:border-transparent"
                                    placeholder="tu@email.com">
                                <p v-if="queryForm.errors.email" class="text-red-600 text-xs mt-1">{{ queryForm.errors.email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Referencia del producto</label>
                                <input type="text" disabled
                                    class="w-full bg-gray-100 border border-gray-300 px-3 py-2 rounded text-gray-500"
                                    :value="'#' + instrument.id + ' - ' + instrument.marca + ' ' + instrument.modelo">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#1a1a1a] mb-1">Mensaje * <span class="text-xs text-gray-400">(máx. 150 caracteres)</span></label>
                                <textarea v-model="queryForm.message" rows="3" maxlength="150"
                                    class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-[#E87F24] focus:border-transparent"
                                    placeholder="Escribe tu consulta..."></textarea>
                                <div class="flex justify-between mt-1">
                                    <p v-if="queryForm.errors.message" class="text-red-600 text-xs">{{ queryForm.errors.message }}</p>
                                    <p class="text-xs text-gray-400 ml-auto">{{ messageLength }}/150</p>
                                </div>
                            </div>
                            <button v-if="queryValid && !queryForm.processing" type="submit"
                                class="bg-[#E87F24] text-white px-6 py-2 rounded font-bold hover:bg-[#FFC81E] transition-colors">
                                Enviar consulta
                            </button>
                            <div v-else-if="queryForm.processing" class="flex items-center gap-2 text-sm text-[#E87F24]">
                                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                Enviando...
                            </div>
                        </form>
                    </div>
                </div>
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
