<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    contactInfo: Array,
});

const form = useForm({
    name: '',
    email: '',
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
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <input v-model="form.name" type="text" placeholder="Nombre" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24] outline-none">
                                    <p v-if="form.errors.name" class="text-red-600 text-xs mt-1">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <input v-model="form.email" type="email" placeholder="Email" class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24] outline-none">
                                    <p v-if="form.errors.email" class="text-red-600 text-xs mt-1">{{ form.errors.email }}</p>
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
                            <button type="submit" :disabled="form.processing" class="bg-[#E87F24] text-white px-6 py-2 font-bold hover:bg-[#FFC81E] disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors">
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
    </AppLayout>
</template>
