<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-950 text-white">
        <nav class="bg-gray-900 border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center">
                            <img src="/img/tunely_logo.png" alt="Tunely" class="h-10 w-auto">
                        </Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link href="/" class="text-gray-400 hover:text-white transition text-sm">
                            Volver a la tienda
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
            <div class="w-full max-w-3xl">
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>