<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    totalProductos: Number,
    totalPedidos: Number,
    totalUsuarios: Number,
    totalCategorias: Number,
    topProducts: Array,
});

const canvasRef = ref(null);
const ORANGE = '#E87F24';
const BLUE = '#73A5CA';
const DARK = '#1a1a1a';

const drawChart = () => {
    const canvas = canvasRef.value;
    if (!canvas || !props.topProducts?.length) return;

    const ctx = canvas.getContext('2d');
    const dpr = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);

    const W = rect.width;
    const H = rect.height;
    const pad = { top: 30, right: 20, bottom: 80, left: 60 };
    const chartW = W - pad.left - pad.right;
    const chartH = H - pad.top - pad.bottom;

    ctx.clearRect(0, 0, W, H);

    const data = props.topProducts;
    const maxVal = Math.max(...data.map(d => d.value), 1);
    const barW = Math.min(40, chartW / data.length * 0.6);
    const gap = chartW / data.length;

    ctx.font = '12px system-ui, sans-serif';

    // Y axis
    ctx.strokeStyle = '#ccc';
    ctx.lineWidth = 0.5;
    const yTicks = 4;
    for (let i = 0; i <= yTicks; i++) {
        const val = Math.round((maxVal / yTicks) * i);
        const y = pad.top + chartH - (val / maxVal) * chartH;
        ctx.beginPath();
        ctx.moveTo(pad.left, y);
        ctx.lineTo(W - pad.right, y);
        ctx.stroke();
        ctx.fillStyle = DARK;
        ctx.textAlign = 'right';
        ctx.fillText(val.toString(), pad.left - 8, y + 4);
    }

    data.forEach((d, i) => {
        const x = pad.left + i * gap + (gap - barW) / 2;
        const barH = (d.value / maxVal) * chartH;
        const y = pad.top + chartH - barH;

        // Gradient bar
        const grad = ctx.createLinearGradient(x, y, x, pad.top + chartH);
        grad.addColorStop(0, ORANGE);
        grad.addColorStop(1, BLUE);
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.roundRect(x, y, barW, barH, [4, 4, 0, 0]);
        ctx.fill();

        // Value on top
        ctx.fillStyle = DARK;
        ctx.textAlign = 'center';
        ctx.font = 'bold 11px system-ui, sans-serif';
        ctx.fillText(d.value, x + barW / 2, y - 6);

        // Label
        ctx.fillStyle = DARK;
        ctx.font = '10px system-ui, sans-serif';
        ctx.textAlign = 'center';
        const label = d.label.length > 14 ? d.label.substring(0, 13) + '…' : d.label;
        ctx.save();
        ctx.translate(x + barW / 2, pad.top + chartH + 10);
        ctx.rotate(-Math.PI / 6);
        ctx.fillText(label, 0, 0);
        ctx.restore();
    });
};

onMounted(() => {
    drawChart();
    window.addEventListener('resize', drawChart);
});

watch(() => props.topProducts, drawChart);
</script>

<template>
    <AdminLayout>
        <Head title="Panel Admin - Tunely" />

        <h1 class="text-2xl font-bold mb-6 text-[#1a1a1a]">Dashboard</h1>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-[#FEFDDF] p-6 rounded-lg shadow">
                <p class="text-sm text-[#1a1a1a]/60">Productos</p>
                <p class="text-3xl font-bold text-[#E87F24] mt-1">{{ totalProductos }}</p>
            </div>
            <div class="bg-[#FEFDDF] p-6 rounded-lg shadow">
                <p class="text-sm text-[#1a1a1a]/60">Pedidos</p>
                <p class="text-3xl font-bold text-[#E87F24] mt-1">{{ totalPedidos }}</p>
            </div>
            <div class="bg-[#FEFDDF] p-6 rounded-lg shadow">
                <p class="text-sm text-[#1a1a1a]/60">Usuarios</p>
                <p class="text-3xl font-bold text-[#E87F24] mt-1">{{ totalUsuarios }}</p>
            </div>
            <div class="bg-[#FEFDDF] p-6 rounded-lg shadow">
                <p class="text-sm text-[#1a1a1a]/60">Categorías</p>
                <p class="text-3xl font-bold text-[#E87F24] mt-1">{{ totalCategorias }}</p>
            </div>
        </div>

        <div class="bg-[#FEFDDF] p-6 rounded-lg shadow">
            <h2 class="text-lg font-bold text-[#1a1a1a] mb-4">Productos más vendidos</h2>
            <div v-if="!topProducts?.length" class="text-sm text-[#1a1a1a]/60 py-8 text-center">
                No hay ventas todavía
            </div>
            <canvas
                v-else
                ref="canvasRef"
                class="w-full h-72"
            ></canvas>
        </div>
    </AdminLayout>
</template>
