<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    instrument: Object,
    categories: Array,
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
    disponible: props.instrument?.disponible ?? true,
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
            <Link :href="route('admin.instrumentos.index')" class="text-[#E87F24] hover:underline text-sm">← Volver a productos</Link>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ instrument ? 'Editar producto' : 'Nuevo producto' }}</h1>
        </div>

        <form @submit.prevent="submit" class="bg-white rounded shadow p-6 max-w-2xl space-y-5">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
                    <input type="text" v-model="form.marca" class="w-full border border-gray-300 px-3 py-2 rounded" required>
                    <p v-if="form.errors.marca" class="text-red-500 text-xs mt-1">{{ form.errors.marca }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modelo *</label>
                    <input type="text" v-model="form.modelo" class="w-full border border-gray-300 px-3 py-2 rounded" required>
                    <p v-if="form.errors.modelo" class="text-red-500 text-xs mt-1">{{ form.errors.modelo }}</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <select v-model="form.tipo" class="w-full border border-gray-300 px-3 py-2 rounded bg-white" required>
                        <option value="nuevo">Nuevo</option>
                        <option value="usado">Usado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select v-model="form.category_id" class="w-full border border-gray-300 px-3 py-2 rounded bg-white" required>
                        <option value="">Selecciona...</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                    </select>
                    <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio (€) *</label>
                    <input type="number" step="0.01" min="0" v-model="form.precio" class="w-full border border-gray-300 px-3 py-2 rounded" required>
                    <p v-if="form.errors.precio" class="text-red-500 text-xs mt-1">{{ form.errors.precio }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio original (€)</label>
                    <input type="number" step="0.01" min="0" v-model="form.precio_original" class="w-full border border-gray-300 px-3 py-2 rounded" placeholder="Para mostrar descuento">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                    <input type="number" min="0" v-model="form.stock" class="w-full border border-gray-300 px-3 py-2 rounded" required>
                    <p v-if="form.errors.stock" class="text-red-500 text-xs mt-1">{{ form.errors.stock }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL de la imagen</label>
                <input type="url" v-model="form.imagen" class="w-full border border-gray-300 px-3 py-2 rounded" placeholder="https://...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea v-model="form.descripcion" rows="4" class="w-full border border-gray-300 px-3 py-2 rounded"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="disponible" v-model="form.disponible" class="rounded">
                <label for="disponible" class="text-sm text-gray-700">Producto disponible para la venta</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[#E87F24] text-white px-6 py-2 rounded font-bold hover:bg-[#FFC81E]">
                    {{ instrument ? 'Guardar cambios' : 'Crear producto' }}
                </button>
                <Link :href="route('admin.instrumentos.index')" class="px-6 py-2 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    Cancelar
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
