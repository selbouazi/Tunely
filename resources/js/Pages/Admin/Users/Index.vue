<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    users: Array,
});

const search = ref('');
const sortField = ref(null);
const sortDir = ref('asc');
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const targetUser = ref(null);

const editForm = useForm({
    name: '',
    email: '',
    role: 'client',
});

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
    let list = [...props.users];
    if (search.value) {
        const q = search.value.toLowerCase();
        list = list.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
    }
    if (sortField.value) {
        list.sort((a, b) => {
            let va, vb;
            switch (sortField.value) {
                case 'role': va = a.role; vb = b.role; break;
                case 'orders': va = a.orders_count ?? 0; vb = b.orders_count ?? 0; break;
                case 'created_at': va = a.created_at; vb = b.created_at; break;
                default: va = a.name.toLowerCase(); vb = b.name.toLowerCase(); break;
            }
            if (va < vb) return sortDir.value === 'asc' ? -1 : 1;
            if (va > vb) return sortDir.value === 'asc' ? 1 : -1;
            return 0;
        });
    }
    return list;
});

const openEdit = (user) => {
    targetUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.role;
    showEditModal.value = true;
};

const saveEdit = () => {
    if (targetUser.value) {
        editForm.patch(route('admin.usuarios.update', targetUser.value.id), {
            onSuccess: () => { showEditModal.value = false; targetUser.value = null; }
        });
    }
};

const confirmDelete = (user) => {
    targetUser.value = user;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (targetUser.value) {
        editForm.delete(route('admin.usuarios.destroy', targetUser.value.id), {
            onSuccess: () => { showDeleteModal.value = false; targetUser.value = null; }
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Usuarios - Admin Tunely" />

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Usuarios</h1>
            <input
                v-model="search"
                type="text"
                placeholder="Buscar por nombre o email..."
                class="border border-gray-300 px-3 py-2 rounded text-sm bg-white focus:ring-2 focus:ring-[#E87F24] focus:border-transparent w-full sm:w-64"
            >
        </div>

        <div class="text-sm text-gray-500 mb-4">{{ filtered.length }} usuario{{ filtered.length !== 1 ? 's' : '' }}</div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('name')">
                            Nombre<span class="text-xs" v-html="sortIcon('name')"></span>
                        </th>
                        <th class="text-left px-4 py-3">Email</th>
                        <th class="text-center px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('role')">
                            Rol<span class="text-xs" v-html="sortIcon('role')"></span>
                        </th>
                        <th class="text-center px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('orders')">
                            Pedidos<span class="text-xs" v-html="sortIcon('orders')"></span>
                        </th>
                        <th class="text-center px-4 py-3">Valoraciones</th>
                        <th class="text-center px-4 py-3 cursor-pointer select-none hover:text-[#E87F24]" @click="toggleSort('created_at')">
                            Registro<span class="text-xs" v-html="sortIcon('created_at')"></span>
                        </th>
                        <th class="text-right px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="u in filtered" :key="u.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ u.name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ u.email }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded font-medium"
                                :class="u.role === 'admin' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600'"
                            >{{ u.role }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">{{ u.orders_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-center">{{ u.ratings_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-center text-gray-500">{{ new Date(u.created_at).toLocaleDateString('es-ES') }}</td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <button @click="openEdit(u)" class="text-[#E87F24] hover:underline text-sm font-medium">Editar</button>
                            <button @click="confirmDelete(u)" class="text-red-500 hover:underline text-sm">Eliminar</button>
                        </td>
                    </tr>
                    <tr v-if="filtered.length === 0">
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No hay usuarios</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="showEditModal" @close="showEditModal = false" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Editar usuario</h2>
                <form @submit.prevent="saveEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input v-model="editForm.name" type="text" class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-[#E87F24] focus:border-transparent">
                        <p v-if="editForm.errors.name" class="text-red-600 text-xs mt-1">{{ editForm.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="editForm.email" type="email" class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-[#E87F24] focus:border-transparent">
                        <p v-if="editForm.errors.email" class="text-red-600 text-xs mt-1">{{ editForm.errors.email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <select v-model="editForm.role" class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-[#E87F24] focus:border-transparent">
                            <option value="client">Cliente</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <p v-if="editForm.errors.role" class="text-red-600 text-xs mt-1">{{ editForm.errors.role }}</p>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm text-white bg-[#E87F24] rounded hover:bg-[#FFC81E] transition-colors disabled:bg-gray-400">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showDeleteModal" @close="showDeleteModal = false" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Eliminar usuario</h2>
                <p class="text-gray-600 text-sm">
                    ¿Estás seguro de que quieres eliminar a <strong>{{ targetUser?.name }}</strong> ({{ targetUser?.email }})?
                </p>
                <p class="text-gray-500 text-xs mt-1">Esta acción no se puede deshacer.</p>
                <div class="flex justify-end gap-3 mt-6">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="executeDelete" :disabled="editForm.processing" class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700 disabled:bg-gray-400">
                        Eliminar
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
