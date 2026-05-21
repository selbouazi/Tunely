<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    messages: Array,
});
</script>

<template>
    <AdminLayout>
        <Head title="Mensajes - Tunely Admin" />

        <h1 class="text-2xl font-bold mb-6 text-[#1a1a1a]">Mensajes de contacto</h1>

        <div v-if="!messages.length" class="bg-white p-6 rounded-lg shadow text-center text-[#1a1a1a]/60">
            No hay mensajes todavía
        </div>

        <div v-else class="space-y-4">
            <div v-for="msg in messages" :key="msg.id" class="bg-white p-5 rounded-lg shadow">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                    <div class="font-medium text-[#1a1a1a]">{{ msg.name }} <span class="text-sm font-normal text-[#1a1a1a]/60">&lt;{{ msg.email }}&gt;</span></div>
                    <div class="text-xs text-[#1a1a1a]/50">{{ new Date(msg.created_at).toLocaleString('es-ES') }}</div>
                </div>
                <div class="text-xs text-[#E87F24] font-medium mb-2">{{ msg.subject }}</div>
                <p class="text-sm text-[#1a1a1a]/80 whitespace-pre-wrap">{{ msg.message }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
