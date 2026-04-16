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
        class="rounded-md border-[#73A5CA] shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24]"
        v-model="model"
        ref="input"
    />
</template>
