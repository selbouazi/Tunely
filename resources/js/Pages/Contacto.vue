<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    contactInfo: Array,
});

const getByType = (type) => {
    const item = props.contactInfo.find(i => i.type === type);
    return item ? item.value : '';
};
</script>

<template>
    <AppLayout>
        <Head title="Contacto - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold mb-4 text-[#1a1a1a]">Contacto</h1>
                <p class="text-[#1a1a1a]/70">Estamos aquí para ayudarte</p>
            </div>
        </section>

        <section class="bg-white py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-xl font-bold mb-6 text-[#1a1a1a]">Envíanos un mensaje</h2>
                        <form class="space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <input type="text" placeholder="Nombre" class="bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24]">
                                <input type="text" placeholder="Apellidos" class="bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24]">
                            </div>
                            <input type="email" placeholder="Email" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24]">
                            <select class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24]">
                                <option>Consulta general</option>
                                <option>Queremos vender un instrumento</option>
                                <option>Información sobre instrumentos</option>
                                <option>Otro</option>
                            </select>
                            <textarea rows="4" placeholder="Mensaje" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24]"></textarea>
                            <button type="submit" class="bg-[#E87F24] text-white px-6 py-2 font-bold hover:bg-[#FFC81E]">Enviar</button>
                        </form>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-6 text-[#1a1a1a]">Información de contacto</h2>
                        <div class="space-y-4 text-[#1a1a1a]/70">
                            <p v-for="item in props.contactInfo" :key="item.id">
                                <strong class="text-[#1a1a1a]">{{ item.label }}:</strong> {{ item.value }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>