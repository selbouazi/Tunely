<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout>
        <Head title="Condiciones de Venta - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-[#1a1a1a]">Condiciones de Venta</h1>

                <div class="bg-white p-8 rounded shadow space-y-6 text-[#1a1a1a]/80">
                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">1. Precios y Pagos</h2>
                        <p>Todos los precios incluyen IVA. Aceptamos Visa, Mastercard, Bizum y transferencia bancaria.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">2. Envíos</h2>
                        <p>Realizamos envíos a toda España peninsular en un plazo de 24-72 horas. Los gastos de envío son gratuitos para pedidos superiores a 100€.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">3. Devoluciones</h2>
                        <p>Dispones de 14 días desde la recepción para devolver cualquier producto en perfecto estado y con su embalaje original.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">4. Garantía</h2>
                        <p>Los instrumentos nuevos tienen garantía de fabricante. Los instrumentos usados tienen garantía de 6 meses.</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
