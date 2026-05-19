<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    categories: Array,
});

const showModal = ref(false);
const editingCategory = ref(null);
const showDeleteModal = ref(false);
const targetDeleteId = ref(null);
const targetDeleteName = ref('');

const form = useForm({
    nombre: '',
});

const openCreate = () => {
    editingCategory.value = null;
    form.nombre = '';
    showModal.value = true;
};

const openEdit = (cat) => {
    editingCategory.value = cat;
    form.nombre = cat.nombre;
    showModal.value = true;
};

const submit = () => {
    if (editingCategory.value) {
        form.put(route('admin.categorias.update', editingCategory.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    } else {
        form.post(route('admin.categorias.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    }
};

const confirmDelete = (cat) => {
    targetDeleteId.value = cat.id;
    targetDeleteName.value = cat.nombre;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (targetDeleteId.value) {
        form.delete(route('admin.categorias.destroy', targetDeleteId.value));
        showDeleteModal.value = false;
        targetDeleteId.value = null;
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Categorías - Admin Tunely" />

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Categorías</h1>
            <button @click="openCreate" class="bg-[#E87F24] text-white px-4 py-2 rounded hover:bg-[#FFC81E]">
                + Nueva categoría
            </button>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Nombre</th>
                        <th class="text-left px-4 py-3">Slug</th>
                        <th class="text-center px-4 py-3">Productos</th>
                        <th class="text-right px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cat in categories" :key="cat.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ cat.nombre }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ cat.slug }}</td>
                        <td class="px-4 py-3 text-center">{{ cat.instruments_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button @click="openEdit(cat)" class="text-[#E87F24] hover:underline text-sm">Editar</button>
                            <button @click="confirmDelete(cat)" class="text-red-500 hover:underline text-sm">Eliminar</button>
                        </td>
                    </tr>
                    <tr v-if="categories.length === 0">
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No hay categorías</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showModal = false">
            <div class="bg-white rounded shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">{{ editingCategory ? 'Editar categoría' : 'Nueva categoría' }}</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input
                            type="text"
                            v-model="form.nombre"
                            class="w-full border border-gray-300 px-3 py-2 rounded"
                            placeholder="Ej: Cuerda"
                            required
                        >
                        <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:underline">Cancelar</button>
                        <button type="submit" class="bg-[#E87F24] text-white px-4 py-2 rounded hover:bg-[#FFC81E]">
                            {{ editingCategory ? 'Guardar cambios' : 'Crear categoría' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Eliminar categoría</h2>
                <p class="text-gray-600 text-sm">
                    ¿Estás seguro de que quieres eliminar la categoría <strong>{{ targetDeleteName }}</strong>?
                </p>
                <div class="flex justify-end gap-3 mt-6">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="executeDelete" class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
