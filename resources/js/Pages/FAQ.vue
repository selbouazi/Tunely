<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const faqs = [
    {
        question: '¿Tienen instrumentos nuevos y usados?',
        answer: 'Sí, en Tunely vendemos tanto instrumentos musicales nuevos como de segunda mano. Cada instrumento usado pasa por nuestro control de calidad.'
    },
    {
        question: '¿Cuál es la política de devoluciones?',
        answer: 'Puedes devolver cualquier instrumento en un plazo de 14 días desde la compra, siempre que esté en perfecto estado y con su embalaje original.'
    },
    {
        question: '¿Ofrecen garantía en los instrumentos?',
        answer: 'Sí, todos nuestros instrumentos nuevos incluyen garantía de fabricante. Los instrumentos usados tienen garantía de 6 meses.'
    },
    {
        question: '¿Cómo puedo vender mi instrumento?',
        answer: 'Contáctanos a través de nuestro formulario indicando los datos del instrumento. Te ofreceremos una valoración gratuita en 24-48 horas.'
    },
    {
        question: '¿Realizan envíos a toda España?',
        answer: 'Sí, realizamos envíos a toda España peninsula. Los gastos de envío son gratuitos para pedidos superiores a 100€.'
    },
    {
        question: '¿Qué formas de pago aceptan?',
        answer: 'Aceptamos Visa, Mastercard, Bizum y transferencia bancaria. Todas las transacciones están securizadas.'
    }
];

const openIndex = ref(null);

const toggle = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <AppLayout>
        <Head title="FAQ - Tunely" />

        <section class="bg-[#FEFDDF] py-12 px-4">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold mb-4 text-[#1a1a1a]">Preguntas Frecuentes</h1>
                <p class="text-[#1a1a1a]/70">Resolvemos tus dudas</p>
            </div>
        </section>

        <section class="bg-white py-12 px-4">
            <div class="max-w-3xl mx-auto">
                <div class="space-y-4">
                    <div v-for="(faq, index) in faqs" :key="index" class="border border-[#73A5CA] rounded">
                        <button 
                            @click="toggle(index)"
                            class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-[#FEFDDF]"
                        >
                            <span class="font-medium text-[#1a1a1a]">{{ faq.question }}</span>
                            <svg 
                                class="w-5 h-5 text-[#E87F24] transition-transform" 
                                :class="{ 'rotate-180': openIndex === index }"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="openIndex === index" class="px-4 py-3 text-[#1a1a1a]/70 border-t border-[#73A5CA]">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>