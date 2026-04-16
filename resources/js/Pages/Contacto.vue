<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const contactInfo = {
    address: 'C/ Gran Via, 123',
    city: '08014 Barcelona',
    phone: '+34 93 123 45 67',
    email: 'info@tunely.es',
    hours: 'Lunes - Viernes: 9:00 - 20:00'
};

const mensaje = ref('');
const maxChars = 150;
const sending = ref(false);
const sent = ref(false);

const charCount = () => mensaje.value.length;

const handleSubmit = () => {
    if (mensaje.value.length > maxChars) return;
    sending.value = true;
    setTimeout(() => {
        sending.value = false;
        sent.value = true;
        mensaje.value = '';
        setTimeout(() => { sent.value = false; }, 3000);
    }, 1500);
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
                            <div>
                                <textarea 
                                    v-model="mensaje" 
                                    rows="4" 
                                    placeholder="Mensaje (máx 150 caracteres)" 
                                    maxlength="150"
                                    class="w-full bg-[#FEFDDF] border border-[#73A5CA] px-4 py-2 text-[#1a1a1a] focus:border-[#E87F24]"
                                ></textarea>
                                <p class="text-xs text-[#1a1a1a]/50 text-right">{{ charCount() }}/{{ maxChars }}</p>
                            </div>
                            <button 
                                type="button" 
                                @click="handleSubmit" 
                                :disabled="sending || mensaje.length === 0"
                                class="bg-[#E87F24] text-white px-6 py-2 font-bold hover:bg-[#FFC81E] disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            >
                                <svg v-if="sending" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.878 3 8.219l2-2.928z"></path>
                                </svg>
                                <span v-if="sending">Enviando...</span>
                                <span v-else-if="sent">¡Enviado!</span>
                                <span v-else>Enviar</span>
                            </button>
                        </form>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold mb-6 text-[#1a1a1a]">Información de contacto</h2>
                        <div class="space-y-4 text-[#1a1a1a]/70">
                            <p><strong class="text-[#1a1a1a]">Dirección:</strong> {{ contactInfo.address }}, {{ contactInfo.city }}</p>
                            <p><strong class="text-[#1a1a1a]">Teléfono:</strong> {{ contactInfo.phone }}</p>
                            <p><strong class="text-[#1a1a1a]">Email:</strong> {{ contactInfo.email }}</p>
                            <p><strong class="text-[#1a1a1a]">Horario:</strong> {{ contactInfo.hours }}</p>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-xl font-bold mb-4 text-[#1a1a1a]">Formas de pago</h3>
                            <div class="flex gap-4">
                                <div class="bg-white px-3 py-2 rounded shadow text-center">
                                    <svg class="w-10 h-8 mx-auto mb-1" viewBox="0 0 32 24" fill="none">
                                        <rect width="32" height="24" rx="4" fill="#1A1F71"/>
                                        <text x="4" y="15" fill="white" font-size="8" font-weight="bold">VISA</text>
                                    </svg>
                                    <span class="text-xs text-[#1a1a1a]/70">Visa</span>
                                </div>
                                <div class="bg-white px-3 py-2 rounded shadow text-center">
                                    <svg class="w-10 h-8 mx-auto mb-1" viewBox="0 0 32 24" fill="none">
                                        <rect width="32" height="24" rx="4" fill="#EB001B"/>
                                        <circle cx="12" cy="12" r="7" fill="#EB001B"/>
                                        <circle cx="20" cy="12" r="7" fill="#F79E1B"/>
                                        <path d="M16 6.5c1.5 1.2 2.5 3 2.5 5.5s-1 4.3-2.5 5.5c-1.5-1.2-2.5-3-2.5-5.5s1-4.3 2.5-5.5z" fill="#FF5F00"/>
                                    </svg>
                                    <span class="text-xs text-[#1a1a1a]/70">Mastercard</span>
                                </div>
                                <div class="bg-white px-3 py-2 rounded shadow text-center">
                                    <svg class="w-10 h-8 mx-auto mb-1" viewBox="0 0 32 24">
                                        <rect width="32" height="24" rx="4" fill="#FAFAFA"/>
                                        <text x="6" y="15" fill="#333" font-size="8" font-weight="bold">BIZUM</text>
                                    </svg>
                                    <span class="text-xs text-[#1a1a1a]/70">Bizum</span>
                                </div>
                                <div class="bg-white px-3 py-2 rounded shadow text-center">
                                    <svg class="w-10 h-8 mx-auto mb-1" viewBox="0 0 32 24">
                                        <rect width="32" height="24" rx="4" fill="#073590"/>
                                        <path d="M8 8h3l-2 8h3l-3-8zm0 0l2.5 8h-3l.5-8zm4 0h4l-2 8h-4l2-8zm0 0v8h4l-2-8h-2zm4 0h3l-1 8h-3l1-8z" fill="white"/>
                                    </svg>
                                    <span class="text-xs text-[#1a1a1a]/70">Transferencia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>