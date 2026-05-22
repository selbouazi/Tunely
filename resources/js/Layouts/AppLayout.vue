<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CartDrawer from '@/Components/CartDrawer.vue';

const showingMobileMenu = ref(false);
const cartOpen = ref(false);
const cart = ref([]);
const pendingCount = ref(0);
const pendingItems = ref([]);
const dismissed = ref(false);
const user = usePage().props.auth?.user;
const isAdmin = computed(() => user?.role === 'admin');

const STORAGE_KEY = 'tunely_cart';

const loadCart = () => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) {
        cart.value = JSON.parse(stored);
    }
};

const saveCart = () => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart.value));
};

const addToCart = (product) => {
    const existing = cart.value.find(item => item.id === product.id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.value.push({
            id: product.id,
            marca: product.marca,
            modelo: product.modelo,
            precio: product.precio,
            imagen: product.imagen,
            quantity: 1
        });
    }
    saveCart();
};

const updateQuantity = (productId, quantity) => {
    const item = cart.value.find(item => item.id === productId);
    if (item) {
        if (quantity <= 0) {
            cart.value = cart.value.filter(i => i.id !== productId);
        } else {
            item.quantity = quantity;
        }
        saveCart();
    }
};

const removeItem = (productId) => {
    cart.value = cart.value.filter(item => item.id !== productId);
    saveCart();
};

const clearCart = () => {
    cart.value = [];
    saveCart();
};

const cartCount = () => {
    return cart.value.reduce((count, item) => count + item.quantity, 0);
};

onMounted(() => {
    loadCart();

    window.addEventListener('add-to-cart', (e) => {
        addToCart(e.detail);
    });

    if (!isAdmin.value && route().has('api.pending-comments') && usePage().props.auth.user) {
        fetch(route('api.pending-comments'))
            .then(r => r.json())
            .then(data => {
                pendingCount.value = data.count;
                pendingItems.value = data.items;
            })
            .catch(() => {});
    }
});
</script>

<template>
    <div class="min-h-screen flex flex-col bg-[#FEFDDF] text-[#1a1a1a]">
        <nav class="bg-[#73A5CA] text-[#1a1a1a] sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-14">
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center">
                            <img src="/img/tunely_logo2.png" alt="Tunely" class="h-28 w-auto">
                        </Link>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <Link href="/" class="text-[#1a1a1a] hover:text-[#E87F24]">Inicio</Link>
                        <Link href="/catalogo" class="text-[#1a1a1a] hover:text-[#E87F24]">Catálogo</Link>
                        <Link href="/quien-somos" class="text-[#1a1a1a] hover:text-[#E87F24]">Quiénes Somos</Link>
                        <Link href="/faq" class="text-[#1a1a1a] hover:text-[#E87F24]">FAQ</Link>
                        <Link href="/contacto" class="text-[#1a1a1a] hover:text-[#E87F24]">Contacto</Link>
                        <Link v-if="$page.props.auth.user && !isAdmin" href="/mis-valoraciones" class="text-[#E87F24] text-sm hover:text-[#FFC81E] font-medium">Mis valoraciones</Link>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button v-if="!isAdmin" @click="cartOpen = true" class="relative p-2 text-[#1a1a1a] hover:text-[#E87F24]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span v-if="cartCount() > 0" class="absolute -top-1 -right-1 bg-[#E87F24] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ cartCount() }}
                            </span>
                        </button>

                        <template v-if="$page.props.auth.user">
                            <Link :href="route('profile.edit')" class="hidden sm:flex items-center gap-2 text-sm text-[#1a1a1a]">
                                <img :src="$page.props.auth.user.foto || '/img/profiles/default.webp'" alt="Foto" class="w-7 h-7 rounded-full object-cover border border-gray-200">
                                {{ $page.props.auth.user.name }}
                            </Link>
                            <Link v-if="isAdmin" href="/admin/dashboard" class="text-[#E87F24] text-sm font-bold hidden sm:inline hover:text-[#FFC81E]">Panel</Link>
                            <Link href="/logout" method="post" as="button" class="text-[#1a1a1a]/70 text-sm hidden sm:inline">Cerrar sesión</Link>
                        </template>
                        <template v-else>
                            <Link href="/login" class="text-[#1a1a1a] text-sm hidden sm:inline">Iniciar sesión</Link>
                            <Link href="/register" class="bg-[#FFC81E] text-[#1a1a1a] px-3 py-1 text-sm font-bold hover:bg-[#E87F24] hidden sm:inline">Registro</Link>
                        </template>

                        <button @click="showingMobileMenu = !showingMobileMenu" class="md:hidden p-2 text-[#1a1a1a]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showingMobileMenu" class="md:hidden bg-[#73A5CA]">
                <div class="px-4 py-2 space-y-1">
                    <Link href="/" class="block py-2 text-[#1a1a1a]">Inicio</Link>
                    <Link href="/catalogo" class="block py-2 text-[#1a1a1a]">Catálogo</Link>
                    <Link href="/quien-somos" class="block py-2 text-[#1a1a1a]">Quiénes Somos</Link>
                    <Link href="/faq" class="block py-2 text-[#1a1a1a]">FAQ</Link>
                    <Link href="/contacto" class="block py-2 text-[#1a1a1a]">Contacto</Link>
                    <hr class="border-[#1a1a1a]/20 my-2">
                    <template v-if="$page.props.auth.user">
                        <div class="flex items-center gap-3 py-2">
                            <img :src="$page.props.auth.user.foto || '/img/profiles/default.webp'" alt="Foto" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            <span class="font-medium text-[#1a1a1a]">{{ $page.props.auth.user.name }}</span>
                        </div>
                        <Link v-if="$page.props.auth.user.role === 'admin'" href="/admin/dashboard" class="block py-2 text-[#E87F24] font-bold">Panel Admin</Link>
                        <Link v-if="!isAdmin" href="/mis-valoraciones" class="block py-2 text-[#1a1a1a]">Mis valoraciones</Link>
                        <Link :href="route('profile.edit')" class="block py-2 text-[#1a1a1a]">Perfil</Link>
                        <Link href="/logout" method="post" as="button" class="block py-2 text-[#1a1a1a]/70">Cerrar sesión</Link>
                    </template>
                    <template v-else>
                        <Link href="/login" class="block py-2 text-[#1a1a1a]">Iniciar sesión</Link>
                        <Link href="/register" class="block py-2 text-[#1a1a1a] font-bold">Registro</Link>
                    </template>
                </div>
            </div>
        </nav>

        <div v-if="!isAdmin && pendingCount > 0 && !dismissed" class="bg-[#FFC81E] text-[#1a1a1a] text-sm text-center py-2 px-4">
            <span>Tienes <strong>{{ pendingCount }}</strong> {{ pendingCount === 1 ? 'producto pendiente' : 'productos pendientes' }} de valorar.
                <Link href="/mis-valoraciones" class="underline font-medium">Valorar ahora</Link>
            </span>
            <button @click="dismissed = true" class="ml-3 font-bold hover:text-[#E87F24]">&times;</button>
        </div>

        <main class="flex-grow">
            <slot :add-to-cart="addToCart" />
        </main>

        <CartDrawer v-if="!isAdmin"
            :cart="cart" 
            :is-open="cartOpen"
            @close="cartOpen = false"
            @update-quantity="updateQuantity"
            @remove-item="removeItem"
            @clear-cart="clearCart"
        />

        <footer class="bg-[#73A5CA] text-[#1a1a1a] py-8">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid md:grid-cols-3 gap-8 text-sm">
                    <div>
                        <img src="/img/tunely_logo.png" alt="Tunely" class="h-16 mb-4">
                        <p>Tu tienda de instrumentos musicales.</p>
                    </div>
                    <div>
                        <h3 class="font-bold mb-2">Enlaces</h3>
                        <ul class="space-y-1">
                            <li><Link href="/catalogo">Catálogo</Link></li>
                            <li><Link href="/quien-somos">Quiénes Somos</Link></li>
                            <li><Link href="/faq">FAQ</Link></li>
                            <li><Link href="/contacto">Contacto</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold mb-2">Legal</h3>
                        <ul class="space-y-1">
                            <li><Link href="/aviso-legal">Aviso Legal</Link></li>
                            <li><Link href="/privacidad">Privacidad</Link></li>
                            <li><Link href="/condiciones">Condiciones</Link></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-6 flex justify-center gap-6">
                    <a href="https://instagram.com/tunely" target="_blank" rel="noopener noreferrer" class="text-[#1a1a1a] hover:text-[#E87F24] transition-colors" title="Instagram">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://facebook.com/tunely" target="_blank" rel="noopener noreferrer" class="text-[#1a1a1a] hover:text-[#E87F24] transition-colors" title="Facebook">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://tiktok.com/@tunely" target="_blank" rel="noopener noreferrer" class="text-[#1a1a1a] hover:text-[#E87F24] transition-colors" title="TikTok">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    </a>
                </div>
                <div class="mt-4 pt-4 border-t text-center text-sm">
                    © 2026 Tunely
                </div>
            </div>
        </footer>
    </div>
</template>