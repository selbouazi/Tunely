<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    faqs: Array,
});

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
                    <div v-for="(faq, index) in props.faqs" :key="faq.id" class="border border-[#73A5CA] rounded">
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