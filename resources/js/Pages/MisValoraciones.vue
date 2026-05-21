<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    ratings: Array,
    pendingRatings: Array,
});



const totalRatings = computed(() => props.ratings.length);
const totalPending = computed(() => props.pendingRatings.length);
</script>

<template>
    <Head title="Mis Valoraciones - Tunely" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-[#1a1a1a]">Mis Valoraciones</h2>
        </template>

        <div class="py-12 px-4">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-lg shadow">
                        <p class="text-sm text-[#1a1a1a]/60">Valoraciones realizadas</p>
                        <p class="text-2xl font-bold text-[#1a1a1a] mt-1">{{ totalRatings }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg shadow">
                        <p class="text-sm text-[#1a1a1a]/60">Pendientes de valorar</p>
                        <p class="text-2xl font-bold text-[#E87F24] mt-1">{{ totalPending }}</p>
                    </div>
                </div>

                <div v-if="pendingRatings.length > 0" class="mb-8">
                    <h3 class="text-lg font-bold text-[#1a1a1a] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#E87F24]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        Productos pendientes de valorar
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="p in pendingRatings" :key="p.id" class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                            <img :src="p.instrument?.imagen" :alt="p.instrument_name" class="w-16 h-16 object-cover rounded">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-[#1a1a1a] truncate">{{ p.instrument_name }}</p>
                                <Link :href="'/catalogo/' + p.instrument_id" class="text-[#E87F24] text-sm hover:underline font-medium">
                                    Valorar ahora
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-[#1a1a1a] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#E87F24]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Mis valoraciones
                    </h3>
                    <div v-if="ratings.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
                        <p class="text-[#1a1a1a]/70">No has valorado ningún producto todavía.</p>
                        <Link href="/catalogo" class="inline-block mt-4 bg-[#E87F24] text-white px-6 py-2 rounded font-bold hover:bg-[#FFC81E]">
                            Explorar productos
                        </Link>
                    </div>
                    <div v-else class="space-y-4">
                        <div v-for="r in ratings" :key="r.id" class="bg-white rounded-lg shadow p-4 sm:p-5 flex flex-col sm:flex-row gap-4">
                            <Link :href="'/catalogo/' + r.instrument_id" class="flex-shrink-0">
                                <img :src="r.instrument?.imagen" :alt="r.instrument?.marca" class="w-full sm:w-20 h-20 object-cover rounded">
                            </Link>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <div>
                                        <Link :href="'/catalogo/' + r.instrument_id" class="font-medium text-[#1a1a1a] hover:text-[#E87F24]">
                                            {{ r.instrument?.marca }} {{ r.instrument?.modelo }}
                                        </Link>
                                        <div class="flex items-center gap-1 mt-1">
                                            <svg v-for="n in 5" :key="n" class="w-4 h-4" :class="n <= r.rating ? 'text-yellow-500' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="text-xs text-[#1a1a1a]/50 whitespace-nowrap">{{ new Date(r.created_at).toLocaleDateString('es-ES') }}</span>
                                </div>
                                <p v-if="r.comment" class="text-sm text-[#1a1a1a]/70 mt-2">{{ r.comment }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
