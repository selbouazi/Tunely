<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const imgMusic = '/img/carrusel/1.webp';
const videoUrl = '/video/videoMarketing.mp4';
</script>

<template>
    <AppLayout>
        <Head title="Quiénes Somos - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold mb-4 text-[#1a1a1a]">Quiénes Somos</h1>
                <p class="text-[#1a1a1a]/70">Más de 10 años de excelencia musical</p>
            </div>
        </section>

        <section class="bg-white py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-4 text-[#1a1a1a]">Nuestra historia</h2>
                        <p class="text-[#1a1a1a]/70 mb-4">
                            Tunely nace en el año 2015 con la vocación de transformar la manera en que la gente compra instrumentos musicales.
                        </p>
                        <p class="text-[#1a1a1a]/70 mb-4">
                            Con más de 500 instrumentos vendidos y una tasa de satisfacción del 98%, nos hemos consolidado como uno de los referentes del sector.
                        </p>
                        <p class="text-[#1a1a1a]/70">
                            Todos nuestros instrumentos pasan un estricto control de calidad y afinación.
                        </p>
                    </div>
                    <div>
                        <img :src="imgMusic" alt="Tunely" class="w-full rounded">
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-2xl font-bold mb-8 text-center text-[#1a1a1a]">Conoce mejor a Tunely</h2>
                <div class="bg-[#1a1a1a] rounded-lg overflow-hidden shadow-lg">
                    <video controls class="w-full aspect-video">
                        <source :src="videoUrl" type="video/mp4">
                        <track kind="subtitles" src="/video/presentacion.vtt" srclang="es" label="Español" default>
                        Tu navegador no soporta la reproducción de video.
                    </video>
                </div>
            </div>
        </section>

        <section class="bg-[#73A5CA] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold mb-8 text-center text-[#1a1a1a]">Nuestros valores</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <h3 class="text-xl font-bold mb-2 text-[#E87F24]">Calidad</h3>
                        <p class="text-[#1a1a1a]/70">Todos nuestros instrumentos pasan controles exhaustivos.</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold mb-2 text-[#E87F24]">Confianza</h3>
                        <p class="text-[#1a1a1a]/70">Transparencia total en todo el proceso.</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold mb-2 text-[#E87F24]">Servicio</h3>
                        <p class="text-[#1a1a1a]/70">Asistencia personalizada.</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
