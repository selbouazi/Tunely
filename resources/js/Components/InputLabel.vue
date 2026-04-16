<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
<<<<<<< HEAD
    <label class="block text-sm font-medium text-[#1a1a1a]">
=======
    <label class="block text-sm font-medium text-gray-700">
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
