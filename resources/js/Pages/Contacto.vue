<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    contactInfo: Array,
});

const user = usePage().props.auth?.user;
const isLoggedIn = !!user;

const form = useForm({
    name: user?.name ?? '',
    email: user?.email ?? '',
    subject: 'Consulta general',
    message: '',
});

const submit = () => {
    form.post(route('contacto.store'));
};

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
                <div v-if="$page.props.flash?.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ $page.props.flash.success }}
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-xl font-bold mb-6 text-[#1a1a1a]">Envíanos un mensaje</h2>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div v-if="!isLoggedIn" class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <input v-model="form.name" type="text" placeholder="Nombre" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24] outline-none">
                                    <p v-if="form.errors.name" class="text-red-600 text-xs mt-1">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <input v-model="form.email" type="email" placeholder="Email" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24] outline-none">
                                    <p v-if="form.errors.email" class="text-red-600 text-xs mt-1">{{ form.errors.email }}</p>
                                </div>
                            </div>
                            <div v-else class="grid md:grid-cols-2 gap-4">
                                <div class="bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a]/70 text-sm rounded">
                                    <strong>Nombre:</strong> {{ user.name }}
                                </div>
                                <div class="bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a]/70 text-sm rounded">
                                    <strong>Email:</strong> {{ user.email }}
                                </div>
                            </div>
                            <div>
                                <select v-model="form.subject" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24] outline-none">
                                    <option>Consulta general</option>
                                    <option>Queremos vender un instrumento</option>
                                    <option>Información sobre instrumentos</option>
                                    <option>Otro</option>
                                </select>
                                <p v-if="form.errors.subject" class="text-red-600 text-xs mt-1">{{ form.errors.subject }}</p>
                            </div>
                            <div>
                                <textarea v-model="form.message" rows="4" placeholder="Mensaje" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24] outline-none"></textarea>
                                <p v-if="form.errors.message" class="text-red-600 text-xs mt-1">{{ form.errors.message }}</p>
                            </div>
                            <button type="submit" :disabled="form.processing" class="bg-[#E87F24] text-white px-6 py-2 font-bold hover:bg-[#FFC81E] disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors inline-flex items-center gap-2">
                                <svg v-if="form.processing" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                {{ form.processing ? 'Enviando...' : 'Enviar' }}
                            </button>
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
        <section class="bg-white pb-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-xl font-bold mb-6 text-[#1a1a1a]">Cómo llegar</h2>
                <div class="w-full aspect-video max-w-3xl rounded shadow overflow-hidden">
                    <iframe
                        src="https://www.openstreetmap.org/export/embed.html?bbox=2.1600%2C41.3800%2C2.1800%2C41.3900&amp;layer=mapnik&amp;marker=41.3850%2C2.1700"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Ubicación de Tunely"
                    ></iframe>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
