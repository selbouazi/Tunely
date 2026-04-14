<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const contactInfo = {
    address: 'C/ Gran Via, 123',
    city: '08014 Barcelona',
    phone: '+34 93 123 45 67',
    email: 'info@tunely.es',
    hours: 'Lunes - Viernes: 9:00 - 20:00'
};
</script>

<template>
    <AppLayout>
        <Head title="Contacto - Tunely" />

        <section class="bg-black py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold mb-4">Contacto</h1>
                <p class="text-gray-400">Estamos aquí para ayudarte</p>
            </div>
        </section>

        <section class="bg-gray-900 py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-xl font-bold mb-6">Envíanos un mensaje</h2>
                        <form class="space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <input type="text" placeholder="Nombre" class="bg-black border border-gray-700 px-4 py-2 text-white focus:border-[#6AECE1]">
                                <input type="text" placeholder="Apellidos" class="bg-black border border-gray-700 px-4 py-2 text-white focus:border-[#6AECE1]">
                            </div>
                            <input type="email" placeholder="Email" class="w-full bg-black border border-gray-700 px-4 py-2 text-white focus:border-[#6AECE1]">
                            <select class="w-full bg-black border border-gray-700 px-4 py-2 text-white focus:border-[#6AECE1]">
                                <option>Consulta general</option>
                                <option>Queremos vender un instrumento</option>
                                <option>Información sobre instrumentos</option>
                                <option>Otro</option>
                            </select>
                            <textarea rows="4" placeholder="Mensaje" class="w-full bg-black border border-gray-700 px-4 py-2 text-white focus:border-[#6AECE1]"></textarea>
                            <button type="submit" class="bg-[#6AECE1] text-black px-6 py-2 font-bold hover:bg-[#26CCC2]">Enviar</button>
                        </form>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-6">Información de contacto</h2>
                        <div class="space-y-4 text-gray-400">
                            <p><strong class="text-white">Dirección:</strong> {{ contactInfo.address }}, {{ contactInfo.city }}</p>
                            <p><strong class="text-white">Teléfono:</strong> {{ contactInfo.phone }}</p>
                            <p><strong class="text-white">Email:</strong> {{ contactInfo.email }}</p>
                            <p><strong class="text-white">Horario:</strong> {{ contactInfo.hours }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>