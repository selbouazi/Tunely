<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#E87F24] text-start text-base font-medium text-[#1a1a1a] bg-[#E87F24]/10 focus:outline-none focus:text-[#E87F24] focus:bg-[#E87F24]/20 focus:border-[#E87F24] transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#1a1a1a]/80 hover:text-[#E87F24] hover:bg-[#E87F24]/5 hover:border-[#E87F24]/50 focus:outline-none focus:text-[#E87F24] focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
