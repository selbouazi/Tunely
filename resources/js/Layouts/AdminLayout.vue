<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingSidebar = ref(false);
const user = usePage().props.auth.user;

const routeName = computed(() => usePage().url);

const isActive = (path) => routeName.value.startsWith(path);
</script>

<template>
    <div class="min-h-screen bg-[#FEFDDF] flex">
        <aside
            class="bg-[#1a1a1a] text-white w-64 flex-shrink-0 hidden md:flex flex-col"
            :class="{ 'fixed inset-0 z-50 flex md:relative': showingSidebar }"
        >
            <div class="p-4 border-b border-gray-700">
                <Link href="/admin/dashboard" class="text-xl font-bold text-[#FFC81E]">Tunely Admin</Link>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                <Link href="/admin/dashboard"
                    class="block px-3 py-2 rounded transition-colors text-sm"
                    :class="isActive('/admin/dashboard') ? 'bg-[#E87F24] text-white' : 'text-white hover:bg-[#E87F24] hover:text-white'">
                    Dashboard
                </Link>
                <Link href="/admin/categorias"
                    class="block px-3 py-2 rounded transition-colors text-sm"
                    :class="isActive('/admin/categorias') ? 'bg-[#E87F24] text-white' : 'text-white hover:bg-[#E87F24] hover:text-white'">
                    Categorías
                </Link>
                <Link href="/admin/subcategorias"
                    class="block px-3 py-2 rounded transition-colors text-sm"
                    :class="isActive('/admin/subcategorias') ? 'bg-[#E87F24] text-white' : 'text-white hover:bg-[#E87F24] hover:text-white'">
                    Subcategorías
                </Link>
                <Link href="/admin/productos"
                    class="block px-3 py-2 rounded transition-colors text-sm"
                    :class="isActive('/admin/productos') ? 'bg-[#E87F24] text-white' : 'text-white hover:bg-[#E87F24] hover:text-white'">
                    Productos
                </Link>
                <Link href="/admin/pedidos"
                    class="block px-3 py-2 rounded transition-colors text-sm"
                    :class="isActive('/admin/pedidos') ? 'bg-[#E87F24] text-white' : 'text-white hover:bg-[#E87F24] hover:text-white'">
                    Pedidos
                </Link>
                <Link href="/admin/opiniones"
                    class="block px-3 py-2 rounded transition-colors text-sm"
                    :class="isActive('/admin/opiniones') ? 'bg-[#E87F24] text-white' : 'text-white hover:bg-[#E87F24] hover:text-white'">
                    Opiniones
                </Link>
                <Link href="/admin/mensajes"
                    class="block px-3 py-2 rounded transition-colors text-sm"
                    :class="isActive('/admin/mensajes') ? 'bg-[#E87F24] text-white' : 'text-white hover:bg-[#E87F24] hover:text-white'">
                    Mensajes
                </Link>
                <hr class="border-gray-700 my-2">
                <Link href="/" class="block px-3 py-2 rounded hover:bg-gray-700 text-sm text-gray-400 transition-colors">
                    ← Volver a la tienda
                </Link>
            </nav>

            <div class="p-4 border-t border-gray-700 text-sm text-gray-400">
                {{ user?.name }}
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-[#73A5CA] shadow-sm h-14 flex items-center px-4">
                <button @click="showingSidebar = true" class="md:hidden p-2 text-[#1a1a1a]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="ml-auto flex items-center gap-4 text-sm">
                    <span class="text-[#1a1a1a]/80">{{ user?.email }}</span>
                    <Link href="/dashboard" class="text-[#1a1a1a] hover:text-[#E87F24] transition-colors font-medium">Mi perfil</Link>
                    <Link href="/logout" method="post" as="button" class="text-red-600 hover:text-red-800 transition-colors font-medium">Salir</Link>
                </div>
            </header>

            <div v-if="showingSidebar" class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="showingSidebar = false"></div>

            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
