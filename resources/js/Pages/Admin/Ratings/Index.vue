<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    ratings: Array,
});

const form = useForm({});

const deleteRating = (id) => {
    if (confirm('¿Eliminar esta valoración?')) {
        form.delete(route('admin.opiniones.destroy', id));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Opiniones - Admin Tunely" />

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Opiniones</h1>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Usuario</th>
                        <th class="text-left px-4 py-3">Producto</th>
                        <th class="text-center px-4 py-3">Puntuación</th>
                        <th class="text-left px-4 py-3">Comentario</th>
                        <th class="text-left px-4 py-3">Fecha</th>
                        <th class="text-right px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="r in ratings" :key="r.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ r.user?.name }}</td>
                        <td class="px-4 py-3">{{ r.instrument?.marca }} {{ r.instrument?.modelo }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-yellow-500">{{ '★'.repeat(r.rating) }}{{ '☆'.repeat(5 - r.rating) }}</span>
                        </td>
                        <td class="px-4 py-3 text-[#1a1a1a]/70 max-w-xs truncate">{{ r.comment || '-' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ new Date(r.created_at).toLocaleDateString('es-ES') }}</td>
                        <td class="px-4 py-3 text-right">
                            <button @click="deleteRating(r.id)" class="text-red-500 hover:underline text-sm">Eliminar</button>
                        </td>
                    </tr>
                    <tr v-if="ratings.length === 0">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay opiniones</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
