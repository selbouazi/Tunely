<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
<<<<<<< HEAD
        class="rounded-md border-[#73A5CA] shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24]"
=======
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
>>>>>>> c3ba709c2a6f353371c53604af7192e3ae440f6a
        v-model="model"
        ref="input"
    />
</template>
