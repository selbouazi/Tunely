<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import CartDrawer from '@/Components/CartDrawer.vue';

const showingMobileMenu = ref(false);
const cartOpen = ref(false);
const cart = ref([]);

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
                        <Link href="/contacto" class="text-[#1a1a1a] hover:text-[#E87F24]">Contacto</Link>
                        <Link href="/faq" class="text-[#1a1a1a] hover:text-[#E87F24]">FAQ</Link>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button @click="cartOpen = true" class="relative p-2 text-[#1a1a1a] hover:text-[#E87F24]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span v-if="cartCount() > 0" class="absolute -top-1 -right-1 bg-[#E87F24] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ cartCount() }}
                            </span>
                        </button>

                        <template v-if="$page.props.auth.user">
                            <Link :href="route('profile.edit')" class="text-[#1a1a1a] text-sm">{{ $page.props.auth.user.name }}</Link>
                            <Link href="/logout" method="post" as="button" class="text-[#1a1a1a]/70 text-sm">Cerrar sesión</Link>
                        </template>
                        <template v-else>
                            <Link href="/login" class="text-[#1a1a1a] text-sm">Iniciar sesión</Link>
                            <Link href="/register" class="bg-[#FFC81E] text-[#1a1a1a] px-3 py-1 text-sm font-bold hover:bg-[#E87F24]">Registro</Link>
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
                    <Link href="/contacto" class="block py-2 text-[#1a1a1a]">Contacto</Link>
                    <Link href="/faq" class="block py-2 text-[#1a1a1a]">FAQ</Link>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <slot :add-to-cart="addToCart" />
        </main>

        <CartDrawer 
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
                            <li><Link href="/contacto">Contacto</Link></li>
                            <li><Link href="/faq">FAQ</Link></li>
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
                    <div>
                        <h3 class="font-bold mb-2">Síguenos</h3>
                        <div class="flex gap-3">
                            <a href="https://instagram.com/tunely" target="_blank" class="text-[#1a1a1a] hover:text-[#E87F24]">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="https://facebook.com/tunely" target="_blank" class="text-[#1a1a1a] hover:text-[#E87F24]">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="https://youtube.com/@tunely" target="_blank" class="text-[#1a1a1a] hover:text-[#E87F24]">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.184a3.017 3.017 0 0 0-3.086-3.11c-1.679-.415-7.648-.415-9.912-.415-2.264 0-8.233 0-9.912.415a3.017 3.017 0 0 0-3.086 3.11c-.415 1.678-.415 6.816-.415 6.816s0 5.138.415 6.816a3.017 3.017 0 0 0 3.086 3.11c1.679-.415 7.648-.415 9.912-.415 2.264 0 8.233 0 9.912.415a3.017 3.017 0 0 0 3.086-3.11c.415-1.678.415-6.816.415-6.816s0-5.138-.415-6.816zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-4 border-t text-center text-sm">
                    © 2026 Tunely
                </div>
            </div>
        </footer>
    </div>
</template>