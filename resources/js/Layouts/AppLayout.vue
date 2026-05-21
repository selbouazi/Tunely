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
                <div class="mt-8 pt-4 border-t text-center text-sm">
                    © 2026 Tunely
                </div>
            </div>
        </footer>
    </div>
</template>