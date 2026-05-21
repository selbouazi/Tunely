<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';

const props = defineProps({
    totalProductos: Number,
    totalPedidos: Number,
    totalUsuarios: Number,
    totalCategorias: Number,
    topProducts: Array,
    discountActive: Boolean,
    discountAffected: Number,
});

const descuentoPorcentaje = ref(15);

const applyDiscount = () => {
    if (!descuentoPorcentaje.value || descuentoPorcentaje.value < 1 || descuentoPorcentaje.value > 99) return;
    router.post(route('admin.discount.apply'), { porcentaje: descuentoPorcentaje.value });
};

const removeDiscount = () => {
    router.post(route('admin.discount.remove'));
};

const canvasRef = ref(null);
const ORANGE = '#E87F24';
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

    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 1;
    const yTicks = 4;
    for (let i = 0; i <= yTicks; i++) {
        const val = Math.round((maxVal / yTicks) * i);
        const y = pad.top + chartH - (val / maxVal) * chartH;
        ctx.beginPath();
        ctx.moveTo(pad.left, y);
        ctx.lineTo(W - pad.right, y);
        ctx.stroke();
        ctx.fillStyle = '#9ca3af';
        ctx.textAlign = 'right';
        ctx.fillText(val.toString(), pad.left - 8, y + 4);
    }

    data.forEach((d, i) => {
        const x = pad.left + i * gap + (gap - barW) / 2;
        const barH = (d.value / maxVal) * chartH;
        const y = pad.top + chartH - barH;

        const grad = ctx.createLinearGradient(x, y, x, pad.top + chartH);
        grad.addColorStop(0, '#E87F24');
        grad.addColorStop(1, '#FFC81E');
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.roundRect(x, y, barW, barH, [4, 4, 0, 0]);
        ctx.fill();

        ctx.fillStyle = DARK;
        ctx.textAlign = 'center';
        ctx.font = 'bold 11px system-ui, sans-serif';
        ctx.fillText(d.value, x + barW / 2, y - 6);

        ctx.fillStyle = '#6b7280';
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

const stats = computed(() => [
    { label: 'Productos', value: props.totalProductos, icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', color: '#E87F24', bg: 'bg-orange-50' },
    { label: 'Pedidos', value: props.totalPedidos, icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', color: '#73A5CA', bg: 'bg-blue-50' },
    { label: 'Usuarios', value: props.totalUsuarios, icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z', color: '#10b981', bg: 'bg-green-50' },
    { label: 'Categorías', value: props.totalCategorias, icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', color: '#8b5cf6', bg: 'bg-purple-50' },
]);
</script>

<template>
    <AdminLayout>
        <Head title="Panel Admin - Tunely" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Resumen de la tienda</p>
        </div>

        <div v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error" class="bg-red-100 text-red-800 px-4 py-3 rounded mb-6 text-sm">
            {{ $page.props.flash.error }}
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div
                v-for="stat in stats"
                :key="stat.label"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ stat.label }}</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ stat.value }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="stat.bg">
                        <svg class="w-5 h-5" :style="{ color: stat.color }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="stat.icon" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-5 mb-8">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h2 class="text-base font-semibold text-gray-800 mb-1">Productos más vendidos</h2>
                <p class="text-xs text-gray-400 mb-4">Top 10 productos con más unidades vendidas</p>

                <div v-if="!topProducts?.length" class="text-sm text-gray-400 py-12 text-center">
                    No hay ventas todavía
                </div>

                <div v-else class="w-full" style="height:320px">
                    <canvas ref="canvasRef" class="w-full h-full"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h2 class="text-base font-semibold text-gray-800 mb-1">Acceso rápido</h2>
                <p class="text-xs text-gray-400 mb-4">Gestiona tu tienda</p>
                <div class="space-y-2">
                    <Link href="/admin/productos" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-orange-50 hover:text-[#E87F24] transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Gestionar productos
                    </Link>
                    <Link href="/admin/pedidos" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-orange-50 hover:text-[#E87F24] transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Ver pedidos
                    </Link>
                    <Link href="/admin/mensajes" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-orange-50 hover:text-[#E87F24] transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Mensajes
                    </Link>
                    <Link href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-orange-50 hover:text-[#E87F24] transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Ver tienda
                    </Link>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-1">Descuento general</h2>
            <p class="text-xs text-gray-400 mb-4">Aplica un descuento a todos los productos sin descuento individual</p>

            <div v-if="discountActive" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                Descuento activo en <strong>{{ discountAffected }}</strong> producto{{ discountAffected !== 1 ? 's' : '' }}
            </div>

            <div class="flex items-center gap-3 mb-4">
                <input type="number" v-model="descuentoPorcentaje" min="1" max="99"
                    class="w-20 text-sm rounded border-gray-300 shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24]"
                    placeholder="%">
                <span class="text-sm text-gray-500">% de descuento</span>
            </div>

            <div class="flex gap-2">
                <button @click="applyDiscount"
                    class="bg-[#E87F24] text-white px-4 py-2 text-sm font-bold rounded hover:bg-[#FFC81E] hover:text-[#1a1a1a] transition-colors"
                    :disabled="discountActive">
                    Aplicar descuento
                </button>
                <button @click="removeDiscount"
                    class="border border-red-500 text-red-500 px-4 py-2 text-sm font-bold rounded hover:bg-red-500 hover:text-white transition-colors"
                    :disabled="!discountActive">
                    Quitar descuento
                </button>
            </div>
        </div>
    </AdminLayout>
</template>
