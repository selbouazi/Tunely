<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    instrument: Object,
    categories: Array,
    subcategories: Array,
});

const form = useForm({
    marca: props.instrument?.marca ?? '',
    modelo: props.instrument?.modelo ?? '',
    tipo: props.instrument?.tipo ?? 'nuevo',
    precio: props.instrument?.precio ?? '',
    precio_original: props.instrument?.precio_original ?? '',
    stock: props.instrument?.stock ?? 1,
    imagen: props.instrument?.imagen ?? '',
    descripcion: props.instrument?.descripcion ?? '',
    category_id: props.instrument?.category_id ?? '',
    subcategory_id: props.instrument?.subcategory_id ?? '',
    disponible: props.instrument?.disponible ?? true,
});

const filteredSubcategories = computed(() => {
    if (!form.category_id || !props.subcategories) return [];
    return props.subcategories.filter(s => s.category_id === form.category_id);
});

const onCategoryChange = () => {
    form.subcategory_id = '';
};

const previewError = ref(false);

const previewSrc = computed(() => {
    if (form.imagen) return form.imagen;
    return '';
});

const submit = () => {
    if (props.instrument) {
        form.put(route('admin.instrumentos.update', props.instrument.id));
    } else {
        form.post(route('admin.instrumentos.store'));
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="(instrument ? 'Editar' : 'Nuevo') + ' producto - Admin Tunely'" />

        <div class="mb-6">
            <Link :href="route('admin.instrumentos.index')" class="text-[#E87F24] hover:underline text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a productos
            </Link>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ instrument ? 'Editar producto' : 'Nuevo producto' }}</h1>
        </div>

        <form @submit.prevent="submit" class="bg-white rounded-xl shadow p-6 lg:p-8 space-y-6">
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-5">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
                            <input type="text" v-model="form.marca" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" required placeholder="Ej: Fender">
                            <p v-if="form.errors.marca" class="text-red-500 text-xs mt-1">{{ form.errors.marca }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo *</label>
                            <input type="text" v-model="form.modelo" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" required placeholder="Ej: Stratocaster">
                            <p v-if="form.errors.modelo" class="text-red-500 text-xs mt-1">{{ form.errors.modelo }}</p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                            <select v-model="form.tipo" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg bg-white focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" required>
                                <option value="nuevo">Nuevo</option>
                                <option value="usado">Usado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio (€) *</label>
                            <input type="number" step="0.01" min="0" v-model="form.precio" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" required placeholder="0.00">
                            <p v-if="form.errors.precio" class="text-red-500 text-xs mt-1">{{ form.errors.precio }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                            <input type="number" min="0" v-model="form.stock" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" required>
                            <p v-if="form.errors.stock" class="text-red-500 text-xs mt-1">{{ form.errors.stock }}</p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio original (€)</label>
                            <input type="number" step="0.01" min="0" v-model="form.precio_original" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" placeholder="Para mostrar descuento">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                            <select v-model="form.category_id" @change="onCategoryChange" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg bg-white focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" required>
                                <option value="">Selecciona...</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                            </select>
                            <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subcategoría</label>
                            <select v-model="form.subcategory_id" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg bg-white focus:ring-2 focus:ring-[#E87F24] focus:border-transparent">
                                <option value="">Sin subcategoría</option>
                                <option v-for="sub in filteredSubcategories" :key="sub.id" :value="sub.id">{{ sub.nombre }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ruta de la imagen</label>
                        <input type="text" v-model="form.imagen" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" placeholder="img/prod/mi-producto.webp">
                        <p class="text-xs text-gray-400 mt-1">Ruta local (ej: img/prod/producto.webp). Les imatges van a public/img/</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea v-model="form.descripcion" rows="4" class="w-full border border-gray-300 px-3 py-2.5 rounded-lg focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" placeholder="Descripció del producte..."></textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="disponible" v-model="form.disponible" class="rounded border-gray-300 text-[#E87F24] focus:ring-[#E87F24]">
                        <label for="disponible" class="text-sm text-gray-700">Producte disponible per a la venda</label>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Vista prèvia de la imatge</h3>
                        <div class="aspect-square bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <img
                                :src="previewSrc"
                                @error="previewError = true"
                                @load="previewError = false"
                                alt="Vista prèvia"
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">
                            {{ previewError ? 'Imatge no disponible' : form.imagen || 'Placeholder per defecte' }}
                        </p>
                    </div>

                    <div v-if="instrument" class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Informació</h3>
                        <div class="space-y-1 text-xs text-gray-500">
                            <p>ID: <span class="font-mono">#{{ instrument.id }}</span></p>
                            <p>Creat: {{ new Date(instrument.created_at).toLocaleDateString('es-ES') }}</p>
                            <p>Actualitzat: {{ new Date(instrument.updated_at).toLocaleDateString('es-ES') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" :disabled="form.processing" class="bg-[#E87F24] text-white px-8 py-2.5 rounded-lg font-bold hover:bg-[#FFC81E] transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span v-if="form.processing" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Guardant...
                    </span>
                    <span v-else>{{ instrument ? 'Guardar canvis' : 'Crear producte' }}</span>
                </button>
                <Link :href="route('admin.instrumentos.index')" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancel·lar
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
