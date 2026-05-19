<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingSidebar = ref(false);
const user = usePage().props.auth.user;
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        <aside
            class="bg-[#1a1a1a] text-white w-64 flex-shrink-0 hidden md:flex flex-col"
            :class="{ 'fixed inset-0 z-50 flex md:relative': showingSidebar }"
        >
            <div class="p-4 border-b border-gray-700">
                <Link href="/admin/dashboard" class="text-xl font-bold text-[#FFC81E]">Tunely Admin</Link>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                <Link href="/admin/dashboard" class="block px-3 py-2 rounded hover:bg-gray-700 text-sm">
                    Dashboard
                </Link>
                <Link href="/admin/categorias" class="block px-3 py-2 rounded hover:bg-gray-700 text-sm">
                    Categorías
                </Link>
                <Link href="/admin/productos" class="block px-3 py-2 rounded hover:bg-gray-700 text-sm">
                    Productos
                </Link>
                <Link href="/admin/pedidos" class="block px-3 py-2 rounded hover:bg-gray-700 text-sm">
                    Pedidos
                </Link>
                <hr class="border-gray-700 my-2">
                <Link href="/" class="block px-3 py-2 rounded hover:bg-gray-700 text-sm text-gray-400">
                    ← Volver a la tienda
                </Link>
            </nav>

            <div class="p-4 border-t border-gray-700 text-sm text-gray-400">
                {{ user?.name }}
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm h-14 flex items-center px-4">
                <button @click="showingSidebar = true" class="md:hidden p-2 text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="ml-auto flex items-center gap-4 text-sm">
                    <span class="text-gray-600">{{ user?.email }}</span>
                    <Link href="/dashboard" class="text-[#E87F24] hover:underline">Mi perfil</Link>
                    <Link href="/logout" method="post" as="button" class="text-red-500 hover:underline">Salir</Link>
                </div>
            </header>

            <div v-if="showingSidebar" class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="showingSidebar = false"></div>

            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
