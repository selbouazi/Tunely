<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
<<<<<<< HEAD
        <p class="text-sm text-red-500">
=======
        <p class="text-sm text-red-600">
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
            {{ message }}
        </p>
    </div>
</template>
