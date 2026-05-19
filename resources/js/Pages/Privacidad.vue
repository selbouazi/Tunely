<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout>
        <Head title="Política de Privacidad - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-[#1a1a1a]">Política de Privacidad</h1>

                <div class="bg-white p-8 rounded shadow space-y-6 text-[#1a1a1a]/80">
                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">1. Responsable del Tratamiento</h2>
                        <p>Tunely, con domicilio en C/ Gran Via, 123, 08014 Barcelona, es el responsable del tratamiento de sus datos personales.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">2. Finalidad del Tratamiento</h2>
                        <p>Recopilamos y tratamos sus datos personales con la finalidad de gestionar pedidos, proporcionar soporte y enviar comunicaciones comerciales si ha dado su consentimiento.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">3. Derechos del Usuario</h2>
                        <p>Puede ejercer sus derechos de acceso, rectificación, supresión, limitación, portabilidad y oposición escribiendo a info@tunely.es.</p>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-2 text-[#1a1a1a]">4. Cookies</h2>
                        <p>Este sitio web utiliza cookies propias y de terceros para mejorar la experiencia de navegación. Puede configurar su navegador para rechazarlas.</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
