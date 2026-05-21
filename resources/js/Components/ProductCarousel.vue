<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    interval: {
        type: Number,
        default: 4000,
    },
});

const current = ref(0);
let timer = null;

const next = () => {
    current.value = (current.value + 1) % props.items.length;
};

const prev = () => {
    current.value = (props.items.length + current.value - 1) % props.items.length;
};

const goTo = (index) => {
    current.value = index;
    resetTimer();
};

const resetTimer = () => {
    if (timer) clearInterval(timer);
    timer = setInterval(next, props.interval);
};

onMounted(() => {
    timer = setInterval(next, props.interval);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>

<template>
    <div class="relative w-full overflow-hidden group">
        <div class="relative h-[300px] sm:h-[400px] md:h-[500px]">
            <div
                v-for="(item, index) in items"
                :key="index"
                class="absolute inset-0 transition-all duration-700 ease-in-out"
                :class="{
                    'opacity-100 translate-x-0': current === index,
                    'opacity-0 translate-x-full': current !== index
                }"
            >
                <div class="relative w-full h-full bg-[#1a1a1a]">
                    <img
                        :src="item.image"
                        :alt="item.title"
                        class="w-full h-full object-cover opacity-60"
                    >
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3">{{ item.title }}</h3>
                            <p class="text-base sm:text-lg md:text-xl text-white/80 mb-6">{{ item.subtitle }}</p>
                            <a
                                :href="item.link"
                                class="inline-block bg-[#E87F24] text-white px-6 py-3 font-bold rounded hover:bg-[#FFC81E] transition-colors"
                            >
                                {{ item.cta }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button
            @click="prev"
            class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/40 text-white w-10 h-10 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-black/60"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button
            @click="next"
            class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/40 text-white w-10 h-10 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-black/60"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <button
                v-for="(_, index) in items"
                :key="index"
                @click="goTo(index)"
                class="w-3 h-3 rounded-full transition-all"
                :class="current === index ? 'bg-[#E87F24] w-6' : 'bg-white/60 hover:bg-white/80'"
            ></button>
        </div>
    </div>
</template>
