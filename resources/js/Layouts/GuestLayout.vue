<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col bg-[#FEFDDF] text-[#1a1a1a]">
        <nav class="bg-[#73A5CA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center">
                            <img src="/img/tunely_logo2.png" alt="Tunely" class="h-28 w-auto">
                        </Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link href="/" class="text-[#1a1a1a] hover:text-[#E87F24] transition text-sm">
                            Volver a la tienda
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
            <div class="w-full max-w-3xl">
                <div class="bg-white border border-[#73A5CA] rounded-2xl p-8 shadow-lg">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>