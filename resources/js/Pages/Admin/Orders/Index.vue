<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    orders: Array,
});
</script>

<template>
    <AdminLayout>
        <Head title="Pedidos - Admin Tunely" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pedidos</h1>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error" class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4 text-sm">
            {{ $page.props.flash.error }}
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">ID</th>
                        <th class="text-left px-4 py-3">Cliente</th>
                        <th class="text-right px-4 py-3">Total</th>
                        <th class="text-center px-4 py-3">Estado</th>
                        <th class="text-left px-4 py-3">Fecha</th>
                        <th class="text-right px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in orders" :key="order.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">#{{ order.id }}</td>
                        <td class="px-4 py-3">{{ order.user?.name }}</td>
                        <td class="px-4 py-3 text-right">{{ order.total }}€</td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="text-xs px-2 py-1 rounded"
                                :class="{
                                    'bg-yellow-100 text-yellow-800': order.estado === 'pendiente',
                                    'bg-blue-100 text-blue-800': order.estado === 'pagado',
                                    'bg-purple-100 text-purple-800': order.estado === 'enviado',
                                    'bg-green-100 text-green-800': order.estado === 'entregado',
                                    'bg-red-100 text-red-800': order.estado === 'cancelado',
                                }"
                            >
                                {{ order.estado }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ new Date(order.created_at).toLocaleDateString('es-ES') }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="route('admin.pedidos.show', order.id)" class="text-[#E87F24] hover:underline text-sm">Ver</Link>
                        </td>
                    </tr>
                    <tr v-if="orders.length === 0">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay pedidos</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
