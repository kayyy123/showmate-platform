<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    stats: Object,
    recentProducts: Array,
});

const page = usePage();
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout activeTab="manage">
        <template #header>Dashboard</template>

        <div v-if="stats" class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Total Produk</p>
                <p class="text-2xl font-bold text-on-surface">{{ stats.totalProducts }}</p>
            </div>
            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Aktif</p>
                <p class="text-2xl font-bold text-primary">{{ stats.activeProducts }}</p>
            </div>
            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Checkout</p>
                <p class="text-2xl font-bold text-primary">{{ stats.totalCheckouts }}</p>
            </div>
            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Kunjungan</p>
                <p class="text-2xl font-bold text-primary">{{ stats.totalVisits }}</p>
            </div>
        </div>

        <div v-if="recentProducts?.length" class="mt-6">
            <h3 class="font-bold text-lg text-on-surface mb-3">Produk Terbaru</h3>
            <div class="space-y-2">
                <div
                    v-for="product in recentProducts"
                    :key="product.id"
                    class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl border border-outline"
                >
                    <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.alt_text || product.name"
                        class="w-12 h-12 rounded-lg object-cover"
                    />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-on-surface truncate">{{ product.name }}</p>
                        <p class="text-xs text-on-surface-variant">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                    </div>
                    <span
                        class="text-[10px] px-1.5 py-0.5 rounded border font-bold uppercase"
                        :class="product.is_active ? 'border-primary text-primary' : 'border-on-surface-variant text-on-surface-variant'"
                    >
                        {{ product.is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
