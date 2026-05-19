<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

const page = usePage();
const STORAGE_KEY = 'tunely_cart';

const cart = ref([]);
const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

onMounted(() => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) {
        cart.value = JSON.parse(stored);
    }
});

const saveCart = () => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart.value));
};

const updateQuantity = (id, qty) => {
    const item = cart.value.find(i => i.id === id);
    if (!item) return;
    if (qty <= 0) {
        cart.value = cart.value.filter(i => i.id !== id);
    } else {
        item.quantity = qty;
    }
    saveCart();
};

const form = useForm({
    items: [],
});

const submit = () => {
    if (cart.value.length === 0) return;

    form.items = cart.value.map(item => ({
        id: item.id,
        quantity: item.quantity,
    }));

    form.post(route('checkout.store'));
};

const cartTotal = computed(() => {
    return cart.value.reduce((sum, item) => sum + item.precio * item.quantity, 0).toFixed(2);
});
</script>

<template>
    <AppLayout>
        <Head title="Checkout - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold mb-2 text-[#1a1a1a]">Checkout</h1>
                <p class="text-[#1a1a1a]/70">Revisa tu pedido y confirma la compra</p>
            </div>
        </section>

        <section class="bg-white py-8 px-4">
            <div class="max-w-4xl mx-auto">
                <div v-if="cart.length === 0" class="text-center py-12 text-[#1a1a1a]/70">
                    <p class="mb-4">Tu carrito está vacío</p>
                    <a href="/catalogo" class="text-[#E87F24] underline">Ir al catálogo</a>
                </div>

                <div v-else class="grid md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-4">
                        <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Artículos</h2>
                        <div v-for="item in cart" :key="item.id" class="flex gap-4 border border-gray-200 p-4 rounded">
                            <img :src="item.imagen || defaultImage" :alt="item.marca" class="w-24 h-24 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="font-medium text-[#1a1a1a]">{{ item.marca }} {{ item.modelo }}</h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <button @click="updateQuantity(item.id, item.quantity - 1)" class="bg-[#73A5CA] px-2 py-1 text-sm rounded hover:bg-[#E87F24]">-</button>
                                    <span class="text-[#1a1a1a] font-medium">{{ item.quantity }}</span>
                                    <button @click="updateQuantity(item.id, item.quantity + 1)" class="bg-[#73A5CA] px-2 py-1 text-sm rounded hover:bg-[#E87F24]">+</button>
                                    <button @click="updateQuantity(item.id, 0)" class="ml-2 text-red-500 text-sm hover:underline">Eliminar</button>
                                </div>
                                <p class="text-[#E87F24] font-bold mt-2">{{ (item.precio * item.quantity).toFixed(2) }}€</p>
                            </div>
                            <div class="text-right text-sm text-[#1a1a1a]/70">
                                {{ item.precio }}€ / ud.
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#FEFDDF] p-6 rounded shadow h-fit">
                        <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Resumen</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[#1a1a1a]/70">Artículos ({{ cart.length }})</span>
                                <span class="text-[#1a1a1a]">{{ cartTotal }}€</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#1a1a1a]/70">Envío</span>
                                <span class="text-green-600">Gratis</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-[#E87F24]">{{ cartTotal }}€</span>
                            </div>
                        </div>

                        <p v-if="form.errors.items" class="text-red-600 text-sm mt-4">{{ form.errors.items }}</p>
                        <p v-if="form.errors.error" class="text-red-600 text-sm mt-2">{{ form.errors.error }}</p>

                        <button
                            @click="submit"
                            :disabled="form.processing || cart.length === 0"
                            class="w-full mt-6 bg-[#E87F24] text-white py-3 rounded font-bold hover:bg-[#FFC81E] disabled:bg-gray-400 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Procesando...' : 'Confirmar pedido' }}
                        </button>

                        <p class="text-xs text-[#1a1a1a]/50 text-center mt-4">
                            Al confirmar, aceptas nuestras <a href="/condiciones" class="underline">condiciones</a>.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
