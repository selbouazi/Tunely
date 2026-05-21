<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingSidebar = ref(false);
const user = usePage().props.auth.user;

const routeName = computed(() => usePage().url);

const isActive = (path) => routeName.value.startsWith(path);

const navItems = [
    { path: '/admin/dashboard', label: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { path: '/admin/categorias', label: 'Categorías', icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
    { path: '/admin/subcategorias', label: 'Subcategorías', icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' },
    { path: '/admin/productos', label: 'Productos', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
    { path: '/admin/pedidos', label: 'Pedidos', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' },
    { path: '/admin/usuarios', label: 'Usuarios', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z' },
    { path: '/admin/opiniones', label: 'Opiniones', icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' },
    { path: '/admin/mensajes', label: 'Mensajes', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
];
</script>

<template>
    <div class="min-h-screen bg-[#FEFDDF] flex">
        <Transition name="sidebar">
            <aside
                v-if="showingSidebar"
                class="fixed inset-0 z-50 flex md:hidden"
            >
                <div class="bg-[#1a1a1a] text-white w-72 flex flex-col" @click.stop>
                    <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                        <Link href="/admin/dashboard" class="text-xl font-bold text-[#FFC81E]">Tunely Admin</Link>
                        <button @click="showingSidebar = false" class="text-gray-400 hover:text-white p-1">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                        <Link
                            v-for="item in navItems"
                            :key="item.path"
                            :href="item.path"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-sm"
                            :class="isActive(item.path) ? 'bg-[#E87F24] text-white' : 'text-gray-300 hover:bg-[#E87F24]/20 hover:text-white'"
                            @click="showingSidebar = false"
                        >
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.icon" />
                            </svg>
                            {{ item.label }}
                        </Link>
                        <hr class="border-gray-700 my-3">
                        <Link href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-gray-700 transition-colors" @click="showingSidebar = false">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver a la tienda
                        </Link>
                    </nav>
                    <div class="p-4 border-t border-gray-700 flex items-center gap-3 text-sm text-gray-400">
                        <img :src="user?.foto || '/img/profiles/default.webp'" alt="Foto" class="w-8 h-8 rounded-full object-cover border border-gray-600">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-gray-300">{{ user?.name }}</p>
                            <p class="text-xs truncate">{{ user?.email }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-black/50" @click="showingSidebar = false"></div>
            </aside>
        </Transition>

        <aside class="bg-[#1a1a1a] text-white w-64 flex-shrink-0 hidden md:flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <Link href="/admin/dashboard" class="text-xl font-bold text-[#FFC81E]">Tunely Admin</Link>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <Link
                    v-for="item in navItems"
                    :key="item.path"
                    :href="item.path"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-sm"
                    :class="isActive(item.path) ? 'bg-[#E87F24] text-white' : 'text-gray-300 hover:bg-[#E87F24]/20 hover:text-white'"
                >
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.icon" />
                    </svg>
                    {{ item.label }}
                </Link>
                <hr class="border-gray-700 my-3">
                <Link href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-gray-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a la tienda
                </Link>
            </nav>
            <div class="p-4 border-t border-gray-700 flex items-center gap-3 text-sm text-gray-400">
                <img :src="user?.foto || '/img/profiles/default.webp'" alt="Foto" class="w-8 h-8 rounded-full object-cover border border-gray-600">
                <div class="min-w-0">
                    <p class="truncate font-medium text-gray-300">{{ user?.name }}</p>
                    <p class="text-xs truncate">{{ user?.email }}</p>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-[#73A5CA] shadow-sm h-14 flex items-center px-4 gap-3">
                <button @click="showingSidebar = true" class="md:hidden p-2 text-[#1a1a1a] hover:text-[#E87F24] rounded-lg transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <Link href="/admin/dashboard" class="md:hidden text-lg font-bold text-[#1a1a1a]">Tunely Admin</Link>
                <div class="ml-auto flex items-center gap-3 text-sm">
                    <Link href="/dashboard" class="hidden sm:flex items-center gap-2 text-[#1a1a1a] hover:text-[#E87F24] transition-colors font-medium whitespace-nowrap">
                        <img :src="user?.foto || '/img/profiles/default.webp'" alt="Foto" class="w-7 h-7 rounded-full object-cover border border-gray-200">
                        {{ user?.name }}
                    </Link>
                    <Link href="/logout" method="post" as="button" class="text-red-600 hover:text-red-800 transition-colors font-medium whitespace-nowrap">Salir</Link>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 overflow-x-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.sidebar-enter-active {
    transition: opacity 0.2s ease;
}
.sidebar-leave-active {
    transition: opacity 0.15s ease;
}
.sidebar-enter-from,
.sidebar-leave-to {
    opacity: 0;
}
</style>
