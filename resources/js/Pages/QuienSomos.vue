<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const imgMusic = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=600';
</script>

<template>
    <AppLayout>
        <Head title="Quiénes Somos - Soundly" />

        <section class="bg-black py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold mb-4">Quiénes Somos</h1>
                <p class="text-gray-400">Más de 10 años de excelencia musical</p>
            </div>
        </section>

        <section class="bg-gray-900 py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Nuestra historia</h2>
                        <p class="text-gray-400 mb-4">
                            Soundly nace en el año 2015 con la vocación de transformar la manera en que la gente compra instrumentos musicales.
                        </p>
                        <p class="text-gray-400 mb-4">
                            Con más de 500 instrumentos vendidos y una tasa de satisfacción del 98%, nos hemos consolidado como uno de los referentes del sector.
                        </p>
                        <p class="text-gray-400">
                            Todos nuestros instrumentos pasan un estricto control de calidad y afinación.
                        </p>
                    </div>
                    <div>
                        <img :src="imgMusic" alt="Soundly" class="w-full rounded">
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-black py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold mb-8 text-center">Nuestros valores</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <h3 class="text-xl font-bold mb-2 text-[#6AECE1]">Calidad</h3>
                        <p class="text-gray-400">Todos nuestros instrumentos pasan controles exhaustivos.</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold mb-2 text-[#6AECE1]">Confianza</h3>
                        <p class="text-gray-400">Transparencia total en todo el proceso.</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold mb-2 text-[#6AECE1]">Servicio</h3>
                        <p class="text-gray-400">Asistencia personalizada.</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>