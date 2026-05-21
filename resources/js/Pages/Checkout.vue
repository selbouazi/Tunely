<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

const STORAGE_KEY = 'tunely_cart';

const props = defineProps({
    userData: Object,
});

const cart = ref([]);
const defaultImage = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

const billingSame = ref(true);

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
    shipping_name: props.userData?.name || '',
    shipping_address: props.userData?.direccion || '',
    shipping_city: props.userData?.ciudad || '',
    shipping_province: props.userData?.provincia || '',
    shipping_postal_code: props.userData?.codigo_postal || '',
    shipping_phone: props.userData?.telefono || '',
    billing_same_as_shipping: true,
    billing_name: props.userData?.name || '',
    billing_address: props.userData?.direccion_facturacion || '',
    billing_city: props.userData?.ciudad_facturacion || '',
    billing_province: props.userData?.provincia_facturacion || '',
    billing_postal_code: props.userData?.codigo_postal_facturacion || '',
    card_number: '',
    card_expiry: '',
    card_cvv: '',
});

const submit = () => {
    if (cart.value.length === 0) return;

    const data = {
        items: cart.value.map(item => ({ id: item.id, quantity: item.quantity })),
        shipping_name: form.shipping_name,
        shipping_address: form.shipping_address,
        shipping_city: form.shipping_city,
        shipping_province: form.shipping_province,
        shipping_postal_code: form.shipping_postal_code,
        shipping_phone: form.shipping_phone,
        billing_same_as_shipping: billingSame.value,
        billing_name: form.billing_name,
        billing_address: form.billing_address,
        billing_city: form.billing_city,
        billing_province: form.billing_province,
        billing_postal_code: form.billing_postal_code,
        card_number: form.card_number,
        card_expiry: form.card_expiry,
        card_cvv: form.card_cvv,
    };

    router.post(route('checkout.store'), data, {
        preserveState: true,
        onError: () => {},
    });
};

const cartTotal = computed(() => {
    return cart.value.reduce((sum, item) => sum + item.precio * item.quantity, 0).toFixed(2);
});

const formatExpiry = (e) => {
    let val = e.target.value.replace(/\D/g, '');
    if (val.length >= 2) {
        val = val.substring(0, 2) + '/' + val.substring(2, 4);
    }
    form.card_expiry = val;
};

const formatCardNumber = (e) => {
    form.card_number = e.target.value.replace(/\D/g, '').substring(0, 16);
};

const formatCvv = (e) => {
    form.card_cvv = e.target.value.replace(/\D/g, '').substring(0, 3);
};
</script>

<template>
    <AppLayout>
        <Head title="Checkout - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold mb-2 text-[#1a1a1a]">Checkout</h1>
                <p class="text-[#1a1a1a]/70">Revisa tu pedido y completa los datos para la compra</p>
            </div>
        </section>

        <section class="bg-white py-8 px-4">
            <div class="max-w-5xl mx-auto">
                <div v-if="cart.length === 0" class="text-center py-12 text-[#1a1a1a]/70">
                    <p class="mb-4">Tu carrito está vacío</p>
                    <a href="/catalogo" class="text-[#E87F24] underline">Ir al catálogo</a>
                </div>

                <form v-else @submit.prevent="submit" class="grid lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <div>
                            <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Artículos</h2>
                            <div v-for="item in cart" :key="item.id" class="flex gap-4 border border-gray-200 p-4 rounded mb-3">
                                <img :src="item.imagen || defaultImage" :alt="item.marca" class="w-24 h-24 object-cover rounded">
                                <div class="flex-1">
                                    <h3 class="font-medium text-[#1a1a1a]">{{ item.marca }} {{ item.modelo }}</h3>
                                    <div class="flex items-center gap-2 mt-2">
                                        <button type="button" @click="updateQuantity(item.id, item.quantity - 1)" class="bg-[#73A5CA] px-2 py-1 text-sm rounded hover:bg-[#E87F24]">-</button>
                                        <span class="text-[#1a1a1a] font-medium">{{ item.quantity }}</span>
                                        <button type="button" @click="updateQuantity(item.id, item.quantity + 1)" class="bg-[#73A5CA] px-2 py-1 text-sm rounded hover:bg-[#E87F24]">+</button>
                                        <button type="button" @click="updateQuantity(item.id, 0)" class="ml-2 text-red-500 text-sm hover:underline">Eliminar</button>
                                    </div>
                                    <p class="text-[#E87F24] font-bold mt-2">{{ (item.precio * item.quantity).toFixed(2) }}€</p>
                                </div>
                                <div class="text-right text-sm text-[#1a1a1a]/70">
                                    {{ item.precio }}€ / ud.
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 p-6 rounded">
                            <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Dirección de envío</h2>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Nombre completo</label>
                                    <input v-model="form.shipping_name" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.shipping_name" class="text-red-600 text-xs mt-1">{{ form.errors.shipping_name }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Dirección</label>
                                    <input v-model="form.shipping_address" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.shipping_address" class="text-red-600 text-xs mt-1">{{ form.errors.shipping_address }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Ciudad</label>
                                    <input v-model="form.shipping_city" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.shipping_city" class="text-red-600 text-xs mt-1">{{ form.errors.shipping_city }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Provincia</label>
                                    <input v-model="form.shipping_province" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.shipping_province" class="text-red-600 text-xs mt-1">{{ form.errors.shipping_province }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Código postal</label>
                                    <input v-model="form.shipping_postal_code" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.shipping_postal_code" class="text-red-600 text-xs mt-1">{{ form.errors.shipping_postal_code }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Teléfono</label>
                                    <input v-model="form.shipping_phone" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.shipping_phone" class="text-red-600 text-xs mt-1">{{ form.errors.shipping_phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 p-6 rounded">
                            <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Dirección de facturación</h2>
                            <label class="flex items-center gap-2 mb-4 cursor-pointer">
                                <input type="checkbox" v-model="billingSame" class="rounded border-gray-300 text-[#E87F24] focus:ring-[#E87F24]" />
                                <span class="text-sm text-[#1a1a1a]/80">La misma que la dirección de envío</span>
                            </label>
                            <div v-if="!billingSame" class="grid md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Nombre completo</label>
                                    <input v-model="form.billing_name" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.billing_name" class="text-red-600 text-xs mt-1">{{ form.errors.billing_name }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Dirección</label>
                                    <input v-model="form.billing_address" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.billing_address" class="text-red-600 text-xs mt-1">{{ form.errors.billing_address }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Ciudad</label>
                                    <input v-model="form.billing_city" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.billing_city" class="text-red-600 text-xs mt-1">{{ form.errors.billing_city }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Provincia</label>
                                    <input v-model="form.billing_province" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.billing_province" class="text-red-600 text-xs mt-1">{{ form.errors.billing_province }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Código postal</label>
                                    <input v-model="form.billing_postal_code" type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.billing_postal_code" class="text-red-600 text-xs mt-1">{{ form.errors.billing_postal_code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 p-6 rounded">
                            <h2 class="text-xl font-bold text-[#1a1a1a] mb-4">Pago con tarjeta</h2>
                            <p class="text-xs text-[#1a1a1a]/50 mb-4">* Datos ficticios — esta tienda no procesa pagos reales</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Número de tarjeta</label>
                                    <input :value="form.card_number" @input="formatCardNumber" type="text" placeholder="1234567890123456" maxlength="19" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.card_number" class="text-red-600 text-xs mt-1">{{ form.errors.card_number }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">Fecha de caducidad</label>
                                    <input :value="form.card_expiry" @input="formatExpiry" type="text" placeholder="MM/AA" maxlength="5" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.card_expiry" class="text-red-600 text-xs mt-1">{{ form.errors.card_expiry }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1a1a1a]/80 mb-1">CVV</label>
                                    <input :value="form.card_cvv" @input="formatCvv" type="text" placeholder="123" maxlength="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#E87F24] focus:border-transparent" />
                                    <p v-if="form.errors.card_cvv" class="text-red-600 text-xs mt-1">{{ form.errors.card_cvv }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="bg-[#FEFDDF] p-6 rounded shadow h-fit sticky top-4">
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
                                type="submit"
                                :disabled="form.processing || cart.length === 0"
                                class="w-full mt-6 bg-[#E87F24] text-white py-3 rounded font-bold hover:bg-[#FFC81E] disabled:bg-gray-400 disabled:cursor-not-allowed"
                            >
                                {{ form.processing ? 'Procesando...' : 'Confirmar pedido' }}
                            </button>

                            <p class="text-xs text-[#1a1a1a]/50 text-center mt-4">
                                Al confirmar, aceptas nuestras <Link href="/condiciones" class="underline">condiciones</Link>.
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </AppLayout>
</template>
