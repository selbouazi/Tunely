<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    subcategories: Array,
    categories: Array,
});

const showModal = ref(false);
const editingItem = ref(null);
const showDeleteModal = ref(false);
const targetDeleteId = ref(null);
const targetDeleteName = ref('');

const form = useForm({
    category_id: '',
    nombre: '',
});

const openCreate = () => {
    editingItem.value = null;
    form.reset();
    form.category_id = props.categories.length > 0 ? props.categories[0].id : '';
    showModal.value = true;
};

const openEdit = (item) => {
    editingItem.value = item;
    form.category_id = item.category_id;
    form.nombre = item.nombre;
    showModal.value = true;
};

const submit = () => {
    if (editingItem.value) {
        form.put(route('admin.subcategorias.update', editingItem.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    } else {
        form.post(route('admin.subcategorias.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    }
};

const confirmDelete = (item) => {
    targetDeleteId.value = item.id;
    targetDeleteName.value = item.nombre;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (targetDeleteId.value) {
        form.delete(route('admin.subcategorias.destroy', targetDeleteId.value));
        showDeleteModal.value = false;
        targetDeleteId.value = null;
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Subcategorías - Admin Tunely" />

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Subcategorías</h1>
            <button @click="openCreate" class="bg-[#E87F24] text-white px-4 py-2 rounded hover:bg-[#FFC81E]">
                + Nueva subcategoría
            </button>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Nombre</th>
                        <th class="text-left px-4 py-3">Categoría</th>
                        <th class="text-left px-4 py-3">Slug</th>
                        <th class="text-center px-4 py-3">Productos</th>
                        <th class="text-right px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in subcategories" :key="item.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ item.nombre }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ item.category?.nombre }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ item.slug }}</td>
                        <td class="px-4 py-3 text-center">{{ item.instruments_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button @click="openEdit(item)" class="text-[#E87F24] hover:underline text-sm">Editar</button>
                            <button @click="confirmDelete(item)" class="text-red-500 hover:underline text-sm">Eliminar</button>
                        </td>
                    </tr>
                    <tr v-if="subcategories.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No hay subcategorías</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showModal = false">
            <div class="bg-white rounded shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">{{ editingItem ? 'Editar subcategoría' : 'Nueva subcategoría' }}</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                        <select v-model="form.category_id" class="w-full border border-gray-300 px-3 py-2 rounded bg-white" required>
                            <option value="">Selecciona...</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                        </select>
                        <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" v-model="form.nombre" class="w-full border border-gray-300 px-3 py-2 rounded" placeholder="Ej: Guitarras eléctricas" required>
                        <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:underline">Cancelar</button>
                        <button type="submit" class="bg-[#E87F24] text-white px-4 py-2 rounded hover:bg-[#FFC81E]">
                            {{ editingItem ? 'Guardar cambios' : 'Crear subcategoría' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Eliminar subcategoría</h2>
                <p class="text-gray-600 text-sm">¿Estás seguro de que quieres eliminar la subcategoría <strong>{{ targetDeleteName }}</strong>?</p>
                <div class="flex justify-end gap-3 mt-6">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">Cancelar</button>
                    <button @click="executeDelete" class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">Eliminar</button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
