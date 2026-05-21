<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    orders: Array,
});

const totalSpent = computed(() => {
    return props.orders.reduce((sum, o) => sum + parseFloat(o.total), 0).toFixed(2);
});

const statusColors = {
    pendiente: 'bg-yellow-100 text-yellow-800',
    pagado: 'bg-blue-100 text-blue-800',
    enviado: 'bg-purple-100 text-purple-800',
    entregado: 'bg-green-100 text-green-800',
    cancelado: 'bg-red-100 text-red-800',
};

const statusLabels = {
    pendiente: 'Pendiente',
    pagado: 'Pagado',
    enviado: 'Enviado',
    entregado: 'Entregado',
    cancelado: 'Cancelado',
};
</script>

<template>
    <Head title="Mis Pedidos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-[#1a1a1a]">Mis Pedidos</h2>
        </template>

        <div class="py-12 px-4">
            <div class="mx-auto max-w-7xl">
                <div v-if="orders.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
                    <p class="text-lg text-[#1a1a1a]/70 mb-4">No tienes pedidos todavía</p>
                    <Link href="/catalogo" class="inline-block bg-[#E87F24] text-white px-6 py-2 font-bold rounded hover:bg-[#FFC81E] hover:text-[#1a1a1a] transition-colors">
                        Explorar productos
                    </Link>
                </div>

                <div v-else class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-5 rounded-lg shadow">
                            <p class="text-sm text-[#1a1a1a]/60">Total pedidos</p>
                            <p class="text-2xl font-bold text-[#1a1a1a] mt-1">{{ orders.length }}</p>
                        </div>
                        <div class="bg-white p-5 rounded-lg shadow">
                            <p class="text-sm text-[#1a1a1a]/60">Gasto total</p>
                            <p class="text-2xl font-bold text-[#E87F24] mt-1">{{ totalSpent }}€</p>
                        </div>
                        <div class="bg-white p-5 rounded-lg shadow">
                            <p class="text-sm text-[#1a1a1a]/60">Último pedido</p>
                            <p class="text-2xl font-bold text-[#1a1a1a] mt-1">{{ orders.length ? new Date(orders[0].created_at).toLocaleDateString('es-ES') : '-' }}</p>
                        </div>
                    </div>

                    <div v-for="order in orders" :key="order.id" class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-5 sm:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                <div>
                                    <p class="text-sm text-[#1a1a1a]/60">Pedido #{{ order.id }}</p>
                                    <p class="text-sm text-[#1a1a1a]/60">{{ new Date(order.created_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium" :class="statusColors[order.estado] || 'bg-gray-100 text-gray-800'">
                                    {{ statusLabels[order.estado] || order.estado }}
                                </span>
                            </div>

                            <div class="divide-y divide-[#1a1a1a]/10">
                                <div v-for="item in order.items" :key="item.id" class="flex items-center gap-3 py-2">
                                    <img :src="item.instrument?.imagen" :alt="item.instrument?.marca" class="w-12 h-12 object-cover rounded" />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-[#1a1a1a] truncate">{{ item.instrument?.marca }} {{ item.instrument?.modelo }}</p>
                                        <p class="text-xs text-[#1a1a1a]/60">{{ item.cantidad }} ud. x {{ parseFloat(item.precio_unitario).toFixed(2) }}€</p>
                                    </div>
                                    <p class="text-sm font-bold text-[#E87F24]">{{ (item.precio_unitario * item.cantidad).toFixed(2) }}€</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-4 pt-3 border-t border-[#1a1a1a]/10">
                                <div class="text-sm text-[#1a1a1a]/60">
                                    {{ order.items.length }} {{ order.items.length === 1 ? 'artículo' : 'artículos' }}
                                </div>
                                <div class="text-lg font-bold text-[#E87F24]">{{ parseFloat(order.total).toFixed(2) }}€</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
