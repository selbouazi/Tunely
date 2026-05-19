<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    totalProductos: Number,
    totalPedidos: Number,
    totalUsuarios: Number,
    totalCategorias: Number,
});
</script>

<template>
    <AdminLayout>
        <Head title="Panel Admin - Tunely" />

        <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard</h1>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <p class="text-sm text-gray-500">Productos</p>
                <p class="text-3xl font-bold text-[#E87F24]">{{ totalProductos }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-sm text-gray-500">Pedidos</p>
                <p class="text-3xl font-bold text-[#E87F24]">{{ totalPedidos }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-sm text-gray-500">Usuarios</p>
                <p class="text-3xl font-bold text-[#E87F24]">{{ totalUsuarios }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <p class="text-sm text-gray-500">Categorías</p>
                <p class="text-3xl font-bold text-[#E87F24]">{{ totalCategorias }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
