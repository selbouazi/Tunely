<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    order: Object,
    availableStatuses: Array,
});

const selectedStatus = ref('');
const form = useForm({
    estado: '',
});

const deleteForm = useForm({});

const deleteOrder = () => {
    if (confirm(`¿Eliminar el pedido #${props.order.id} definitivamente? Esta acción no se puede deshacer.`)) {
        deleteForm.delete(route('admin.pedidos.destroy', props.order.id));
    }
};

const changeStatus = () => {
    if (!selectedStatus.value) return;
    if (!confirm(`¿Cambiar el estado del pedido #${props.order.id} a "${selectedStatus.value}"?`)) return;
    form.estado = selectedStatus.value;
    form.patch(route('admin.pedidos.update-status', props.order.id));
};

const statusStyles = {
    pendiente: 'bg-yellow-100 text-yellow-800',
    pagado: 'bg-blue-100 text-blue-800',
    enviado: 'bg-purple-100 text-purple-800',
    entregado: 'bg-green-100 text-green-800',
    cancelado: 'bg-red-100 text-red-800',
};
</script>

<template>
    <AdminLayout>
        <Head :title="'Pedido #' + order.id + ' - Admin Tunely'" />

        <div class="mb-6">
            <Link :href="route('admin.pedidos.index')" class="text-[#E87F24] hover:underline text-sm">← Volver a pedidos</Link>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Pedido #{{ order.id }}</h1>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4 text-sm">
            {{ $page.props.flash.success }}
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Información del pedido</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium text-gray-600">Estado:</span>
                        <span class="ml-2 text-xs px-2 py-1 rounded" :class="statusStyles[order.estado]">{{ order.estado }}</span>
                    </p>
                    <p><span class="font-medium text-gray-600">Total:</span> {{ order.total }}€</p>
                    <p><span class="font-medium text-gray-600">Fecha:</span> {{ new Date(order.created_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}</p>
                </div>

                <div v-if="availableStatuses.length > 0" class="mt-6 pt-4 border-t">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Cambiar estado</h3>
                    <div class="flex gap-2">
                        <select v-model="selectedStatus" class="border border-gray-300 px-3 py-2 rounded text-sm bg-white">
                            <option value="" disabled>Seleccionar...</option>
                            <option v-for="s in availableStatuses" :key="s" :value="s">{{ s }}</option>
                        </select>
                        <button @click="changeStatus" :disabled="!selectedStatus || form.processing"
                            class="bg-[#E87F24] text-white px-4 py-2 rounded text-sm hover:bg-[#FFC81E] disabled:bg-gray-400 disabled:cursor-not-allowed">
                            {{ form.processing ? 'Guardando...' : 'Actualizar' }}
                        </button>
                    </div>
                    <p v-if="form.errors.estado" class="text-red-600 text-xs mt-2">{{ form.errors.estado }}</p>
                    <div class="mt-6 pt-4 border-t">
                        <button @click="deleteOrder" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Eliminar pedido #{{ order.id }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Cliente</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium text-gray-600">Nombre:</span> {{ order.user?.name }}</p>
                    <p><span class="font-medium text-gray-600">Email:</span> {{ order.user?.email }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6 mt-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Productos</h2>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Producto</th>
                        <th class="text-right px-4 py-3">Precio unitario</th>
                        <th class="text-center px-4 py-3">Cantidad</th>
                        <th class="text-right px-4 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in order.items" :key="item.id" class="border-t">
                        <td class="px-4 py-3">{{ item.instrument?.marca }} {{ item.instrument?.modelo }}</td>
                        <td class="px-4 py-3 text-right">{{ item.precio_unitario }}€</td>
                        <td class="px-4 py-3 text-center">{{ item.cantidad }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ (item.precio_unitario * item.cantidad) }}€</td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50 font-bold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3 text-right">{{ order.total }}€</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </AdminLayout>
</template>
