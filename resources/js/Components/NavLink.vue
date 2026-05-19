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
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#E87F24] text-sm font-medium leading-5 text-[#1a1a1a] focus:outline-none focus:border-[#E87F24] transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#1a1a1a]/80 hover:text-[#E87F24] hover:border-[#E87F24]/50 focus:outline-none focus:text-[#E87F24] focus:border-[#E87F24]/50 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
