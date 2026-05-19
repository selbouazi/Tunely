<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Acceso denegado - Tunely" />

    <div class="min-h-screen flex flex-col items-center justify-center bg-[#FEFDDF] text-[#1a1a1a] px-4">
        <div class="text-center max-w-md">
            <div class="text-8xl font-bold text-[#E87F24] mb-4">403</div>
            <h1 class="text-2xl font-bold mb-2">Acceso denegado</h1>
            <p class="text-[#1a1a1a]/60 mb-8">
                No tienes permisos para acceder a esta sección.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <Link
                    href="/"
                    class="bg-[#E87F24] text-white px-6 py-2 rounded font-bold hover:bg-[#FFC81E] inline-block"
                >
                    Volver al inicio
                </Link>
                <Link
                    href="/catalogo"
                    class="border border-[#E87F24] text-[#E87F24] px-6 py-2 rounded font-bold hover:bg-[#E87F24] hover:text-white inline-block"
                >
                    Ver catálogo
                </Link>
            </div>
        </div>
    </div>
</template>
