<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    order: Object,
});

onMounted(() => {
    localStorage.removeItem('tunely_cart');
});
</script>

<template>
    <AppLayout>
        <Head title="Pedido confirmado - Tunely" />

        <section class="bg-[#FEFDDF] py-16 px-4">
            <div class="max-w-2xl mx-auto text-center">
                <div class="text-6xl mb-4">&#10003;</div>
                <h1 class="text-3xl font-bold text-[#1a1a1a] mb-2">¡Pedido confirmado!</h1>
                <p class="text-[#1a1a1a]/70 mb-6">
                    Gracias por tu compra, <strong>{{ order.user.name }}</strong>.
                </p>

                <div class="bg-white p-6 rounded shadow text-left space-y-6">
                    <div class="flex justify-between">
                        <span class="text-[#1a1a1a]/70">Pedido #{{ order.id }}</span>
                        <span class="text-[#1a1a1a]/70">{{ new Date(order.created_at).toLocaleDateString('es-ES') }}</span>
                    </div>

                    <div>
                        <h3 class="font-medium text-[#1a1a1a] mb-2">Artículos</h3>
                        <div class="space-y-2">
                            <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
                                <span>{{ item.instrument.marca }} {{ item.instrument.modelo }} x{{ item.cantidad }}</span>
                                <span>{{ (item.precio_unitario * item.cantidad).toFixed(2) }}€</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <h3 class="font-medium text-[#1a1a1a] mb-1">Dirección de envío</h3>
                            <p class="text-[#1a1a1a]/70">{{ order.shipping_name }}</p>
                            <p class="text-[#1a1a1a]/70">{{ order.shipping_address }}</p>
                            <p class="text-[#1a1a1a]/70">{{ order.shipping_city }}, {{ order.shipping_province }} ({{ order.shipping_postal_code }})</p>
                            <p class="text-[#1a1a1a]/70">{{ order.shipping_phone }}</p>
                        </div>
                        <div>
                            <h3 class="font-medium text-[#1a1a1a] mb-1">Dirección de facturación</h3>
                            <template v-if="order.billing_same_as_shipping">
                                <p class="text-[#1a1a1a]/70 italic">La misma que la de envío</p>
                            </template>
                            <template v-else>
                                <p class="text-[#1a1a1a]/70">{{ order.billing_name }}</p>
                                <p class="text-[#1a1a1a]/70">{{ order.billing_address }}</p>
                                <p class="text-[#1a1a1a]/70">{{ order.billing_city }}, {{ order.billing_province }} ({{ order.billing_postal_code }})</p>
                            </template>
                        </div>
                    </div>

                    <hr>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-[#E87F24]">{{ order.total }}€</span>
                    </div>
                    <div class="text-sm text-[#1a1a1a]/70">
                        <p>Estado: <span class="text-yellow-600 font-medium">Pendiente</span></p>
                    </div>
                </div>

                <div class="mt-6 flex gap-4 justify-center">
                    <Link href="/catalogo" class="bg-[#E87F24] text-white px-6 py-3 rounded font-bold hover:bg-[#FFC81E]">
                        Seguir comprando
                    </Link>
                    <Link v-if="!$page.props.auth.user || $page.props.auth.user.role !== 'admin'" :href="route('dashboard')" class="border border-[#E87F24] text-[#E87F24] px-6 py-3 rounded font-bold hover:bg-[#E87F24] hover:text-white">
                        Mis pedidos
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
