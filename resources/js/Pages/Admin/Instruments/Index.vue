<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    instruments: Array,
    categories: Array,
});

const filterCat = ref('todas');
const sortField = ref(null);
const sortDir = ref('asc');

const toggleSort = (field) => {
    if (sortField.value === field) {
        sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDir.value = 'asc';
    }
};

const sortIcon = (field) => {
    if (sortField.value !== field) return '';
    return sortDir.value === 'asc' ? ' ▲' : ' ▼';
};

const filtered = computed(() => {
    let list = [...props.instruments];
    if (filterCat.value !== 'todas') {
        list = list.filter(i => i.category_id === filterCat.value);
    }
    if (sortField.value) {
        list.sort((a, b) => {
            let va, vb;
            switch (sortField.value) {
                case 'precio': va = a.precio; vb = b.precio; break;
                case 'stock': va = a.stock; vb = b.stock; break;
                case 'vendidos': va = a.order_items_count ?? 0; vb = b.order_items_count ?? 0; break;
                case 'tipo': va = a.tipo; vb = b.tipo; break;
                case 'categoria': va = a.category?.nombre || ''; vb = b.category?.nombre || ''; break;
                default: va = a.marca + a.modelo; vb = b.marca + b.modelo; break;
            }
            if (va < vb) return sortDir.value === 'asc' ? -1 : 1;
            if (va > vb) return sortDir.value === 'asc' ? 1 : -1;
            return 0;
        });
    }
    return list;
});

const form = useForm({ stock: 1 });
const showDeleteModal = ref(false);
const showActivateModal = ref(false);
const targetProduct = ref(null);
const activateStock = ref(1);

const confirmDelete = (instrument) => {
    targetProduct.value = instrument;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (targetProduct.value) {
        form.delete(route('admin.instrumentos.destroy', targetProduct.value.id));
        showDeleteModal.value = false;
        targetProduct.value = null;
    }
};

const confirmActivate = (instrument) => {
    targetProduct.value = instrument;
    activateStock.value = 1;
    showActivateModal.value = true;
};

const executeActivate = () => {
    if (targetProduct.value) {
        form.stock = activateStock.value;
        form.patch(route('admin.instrumentos.activate', targetProduct.value.id));
        showActivateModal.value = false;
        targetProduct.value = null;
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Productos - Admin Tunely" />

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Productos</h1>
            <div class="flex gap-2">
                <Link :href="route('admin.instrumentos.create')" class="bg-[#E87F24] text-white px-4 py-2 rounded hover:bg-[#FFC81E] whitespace-nowrap text-sm font-medium transition-colors">
                    + Nuevo producto
                </Link>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-4">
            <select v-model="filterCat" class="border border-gray-300 px-3 py-2 rounded text-sm bg-white focus:ring-2 focus:ring-[#E87F24] focus:border-transparent">
                <option value="todas">Todas las categorías</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
            </select>
            <span class="text-sm text-gray-500 self-center ml-auto">{{ filtered.length }} producto{{ filtered.length !== 1 ? 's' : '' }}</span>
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('producto')">
                            Producto<span class="text-xs" v-html="sortIcon('producto')"></span>
                        </th>
                        <th class="text-left px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('categoria')">
                            Categoría<span class="text-xs" v-html="sortIcon('categoria')"></span>
                        </th>
                        <th class="text-left px-4 py-3">Subcategoría</th>
                        <th class="text-right px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('precio')">
                            Precio<span class="text-xs" v-html="sortIcon('precio')"></span>
                        </th>
                        <th class="text-center px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('stock')">
                            Stock<span class="text-xs" v-html="sortIcon('stock')"></span>
                        </th>
                        <th class="text-center px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('vendidos')">
                            Vendidos<span class="text-xs" v-html="sortIcon('vendidos')"></span>
                        </th>
                        <th class="text-center px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('tipo')">
                            Tipo<span class="text-xs" v-html="sortIcon('tipo')"></span>
                        </th>
                        <th class="text-center px-4 py-3">Estado</th>
                        <th class="text-right px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="v in filtered" :key="v.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <span class="font-medium">{{ v.marca }} {{ v.modelo }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ v.category?.nombre }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ v.subcategory?.nombre ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ v.precio }}€</td>
                        <td class="px-4 py-3 text-center">
                            <span :class="v.stock === 0 ? 'text-red-600 font-bold' : v.stock <= 3 ? 'text-yellow-600 font-medium' : ''">
                                {{ v.stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">{{ v.order_items_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded" :class="v.tipo === 'nuevo' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600'">
                                {{ v.tipo }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span v-if="v.disponible && v.stock > 0" class="text-green-600 text-xs bg-green-50 px-2 py-1 rounded font-medium">Disponible</span>
                            <span v-else class="text-red-600 text-xs bg-red-50 px-2 py-1 rounded font-medium">Agotado</span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <Link :href="route('admin.instrumentos.edit', v.id)" class="text-[#E87F24] hover:underline text-sm font-medium">Editar</Link>
                            <button
                                v-if="v.disponible && v.stock > 0"
                                @click="confirmDelete(v)"
                                class="text-red-500 hover:underline text-sm"
                            >Desactivar</button>
                            <button
                                v-else
                                @click="confirmActivate(v)"
                                class="text-green-600 hover:underline text-sm font-medium"
                            >Activar</button>
                        </td>
                    </tr>
                    <tr v-if="filtered.length === 0">
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">No hay productos</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Desactivar producto</h2>
                <p class="text-gray-600 text-sm">
                    ¿Estás seguro de que quieres desactivar <strong>{{ targetProduct?.marca }} {{ targetProduct?.modelo }}</strong>?
                </p>
                <p class="text-gray-500 text-xs mt-1">El producto dejará de verse en el catálogo y el stock se pondrá a 0.</p>
                <div class="flex justify-end gap-3 mt-6">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="executeDelete" class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                        Desactivar
                    </button>
                </div>
            </div>
        </Modal>

        <Modal :show="showActivateModal" @close="showActivateModal = false" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Activar producto</h2>
                <p class="text-gray-600 text-sm mb-4">
                    ¿Quieres activar <strong>{{ targetProduct?.marca }} {{ targetProduct?.modelo }}</strong>?
                </p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nuevo stock</label>
                    <input
                        type="number"
                        min="1"
                        v-model="activateStock"
                        class="w-full border border-gray-300 px-3 py-2 rounded"
                        required
                    >
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button @click="showActivateModal = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="executeActivate" class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                        Activar
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
