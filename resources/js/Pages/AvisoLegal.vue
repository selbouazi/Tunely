<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout>
        <Head title="Aviso Legal - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-[#1a1a1a]">Aviso Legal</h1>

                <div class="bg-white p-8 rounded shadow space-y-6 text-[#1a1a1a]/80">
                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">1. Identificación del Titular</h2>
                        <p><strong>Nombre comercial:</strong> Tunely</p>
                        <p><strong>Domicilio social:</strong> C/ Gran Via, 123, 08014 Barcelona</p>
                        <p><strong>Correo electrónico:</strong> info@tunely.es</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">2. Propiedad Intelectual</h2>
                        <p>Todos los contenidos del sitio web, incluyendo imágenes, textos, logotipos y diseños, están protegidos por derechos de propiedad intelectual. Queda prohibida su reproducción sin autorización expresa.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">3. Exención de Responsabilidad</h2>
                        <p>Tunely no se responsabiliza del uso indebido de los contenidos del sitio web ni de los enlaces a terceros que puedan aparecer.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">4. Legislación Aplicable</h2>
                        <p>Las presentes condiciones se rigen por la legislación española.</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
