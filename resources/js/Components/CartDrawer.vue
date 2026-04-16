<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    cart: Array,
    isOpen: Boolean
});

const emit = defineEmits(['close', 'update-quantity', 'remove-item', 'clear-cart']);

const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const getItemTotal = (item) => {
    return (item.precio * item.quantity).toFixed(2);
};

const cartTotal = () => {
    return props.cart.reduce((total, item) => total + (item.precio * item.quantity), 0).toFixed(2);
};
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="emit('close')"></div>
        
<<<<<<< HEAD
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-[#FEFDDF] shadow-xl">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between p-4 border-b border-[#73A5CA]">
                    <h2 class="text-xl font-bold text-[#1a1a1a]">Carrito de compra</h2>
                    <button @click="emit('close')" class="text-[#1a1a1a] hover:text-[#E87F24]">
=======
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-gray-900 shadow-xl">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between p-4 border-b border-gray-800">
                    <h2 class="text-xl font-bold text-white">Carrito de compra</h2>
                    <button @click="emit('close')" class="text-gray-400 hover:text-white">
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
<<<<<<< HEAD
                    <div v-if="cart.length === 0" class="text-center text-[#1a1a1a]/70 py-8">
                        <p class="mb-4">Tu carrito está vacío</p>
                        <Link href="/catalogo" class="text-[#E87F24] underline" @click="emit('close')">
=======
                    <div v-if="cart.length === 0" class="text-center text-gray-400 py-8">
                        <p class="mb-4">Tu carrito está vacío</p>
                        <Link href="/catalogo" class="text-white underline" @click="emit('close')">
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
                            Ver catálogo
                        </Link>
                    </div>

                    <div v-else class="space-y-4">
<<<<<<< HEAD
                        <div v-for="item in cart" :key="item.id" class="flex gap-4 bg-white p-3 rounded shadow">
                            <img :src="item.imagen || defaultImage" :alt="item.marca" class="w-20 h-20 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="text-[#1a1a1a] font-medium">{{ item.marca }} {{ item.modelo }}</h3>
                                <p class="text-[#1a1a1a]/70 text-sm">{{ item.precio }}€</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <button 
                                        @click="emit('update-quantity', item.id, item.quantity - 1)"
                                        class="bg-[#73A5CA] px-2 py-1 text-[#1a1a1a] rounded hover:bg-[#E87F24]"
                                    >-</button>
                                    <span class="text-[#1a1a1a]">{{ item.quantity }}</span>
                                    <button 
                                        @click="emit('update-quantity', item.id, item.quantity + 1)"
                                        class="bg-[#73A5CA] px-2 py-1 text-[#1a1a1a] rounded hover:bg-[#E87F24]"
                                    >+</button>
                                    <button 
                                        @click="emit('remove-item', item.id)"
                                        class="ml-auto text-red-500 hover:text-red-600 text-sm"
                                    >Eliminar</button>
                                </div>
                            </div>
                            <div class="text-[#1a1a1a] font-bold">
=======
                        <div v-for="item in cart" :key="item.id" class="flex gap-4 bg-gray-800 p-3 rounded">
                            <img :src="item.imagen || defaultImage" :alt="item.marca" class="w-20 h-20 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="text-white font-medium">{{ item.marca }} {{ item.modelo }}</h3>
                                <p class="text-gray-400 text-sm">{{ item.precio }}€</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <button 
                                        @click="emit('update-quantity', item.id, item.quantity - 1)"
                                        class="bg-gray-700 px-2 py-1 text-white rounded hover:bg-gray-600"
                                    >-</button>
                                    <span class="text-white">{{ item.quantity }}</span>
                                    <button 
                                        @click="emit('update-quantity', item.id, item.quantity + 1)"
                                        class="bg-gray-700 px-2 py-1 text-white rounded hover:bg-gray-600"
                                    >+</button>
                                    <button 
                                        @click="emit('remove-item', item.id)"
                                        class="ml-auto text-red-400 hover:text-red-300 text-sm"
                                    >Eliminar</button>
                                </div>
                            </div>
                            <div class="text-white font-bold">
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
                                {{ getItemTotal(item) }}€
                            </div>
                        </div>
                    </div>
                </div>

<<<<<<< HEAD
                <div v-if="cart.length > 0" class="border-t border-[#73A5CA] p-4">
                    <div class="flex justify-between mb-4">
                        <span class="text-[#1a1a1a]/70">Total:</span>
                        <span class="text-[#E87F24] text-xl font-bold">{{ cartTotal() }}€</span>
=======
                <div v-if="cart.length > 0" class="border-t border-gray-800 p-4">
                    <div class="flex justify-between mb-4">
                        <span class="text-gray-400">Total:</span>
                        <span class="text-white text-xl font-bold">{{ cartTotal() }}€</span>
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
                    </div>
                    <div class="flex gap-2">
                        <button 
                            @click="emit('clear-cart')"
<<<<<<< HEAD
                            class="px-4 py-2 text-red-500 border border-red-500 rounded hover:bg-red-500 hover:text-white"
=======
                            class="px-4 py-2 text-red-400 border border-red-400 rounded hover:bg-red-400 hover:text-white"
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
                        >
                            Vaciar
                        </button>
                        <Link 
                            href="/checkout" 
<<<<<<< HEAD
                            class="flex-1 bg-[#E87F24] text-white text-center px-4 py-2 rounded hover:bg-[#FFC81E]"
=======
                            class="flex-1 bg-white text-black text-center px-4 py-2 rounded hover:bg-gray-200"
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
                            @click="emit('close')"
                        >
                            Finalizar compra
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>