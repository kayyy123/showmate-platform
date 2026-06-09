<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    stats: Object,
});

const h = computed(() => props.stats);

function formatRp(value) {
    return 'Rp ' + Number(value ?? 0).toLocaleString('id-ID');
}

function formatDate(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' });
}

const barMax = computed(() => Math.max(h.value.peakVisit ?? 1, 1));

const checkoutBarMax = computed(() => Math.max(h.value.peakCheckout ?? 1, 1));

const hasProducts = computed(() => (h.value.productStats?.length ?? 0) > 0);
const hasCheckouts = computed(() => (h.value.recentCheckouts?.length ?? 0) > 0);
</script>

<template>
    <Head title="Statistik" />

    <DashboardLayout activeTab="stats">
        <template #header>Statistik</template>

        <div class="space-y-6 max-w-5xl">

            <!-- Summary Cards -->
            <section aria-labelledby="summary-heading">
                <h2 id="summary-heading" class="sr-only">Ringkasan Performa</h2>
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">

                    <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">
                            Total Produk
                        </p>
                        <p class="text-2xl font-bold text-on-surface">
                            {{ h.totalProducts }}
                            <span v-if="h.activeProducts != null" class="text-sm font-medium text-on-surface-variant"> ({{ h.activeProducts }} aktif)</span>
                        </p>
                    </div>

                    <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">
                            Kunjungan
                        </p>
                        <p class="text-2xl font-bold text-primary">{{ h.totalVisits ?? 0 }}</p>
                    </div>

                    <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">
                            Checkout
                        </p>
                        <p class="text-2xl font-bold text-primary">{{ h.totalCheckouts ?? 0 }}</p>
                    </div>

                    <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">
                            Total Pendapatan
                        </p>
                        <p class="text-2xl font-bold text-on-surface">{{ formatRp(h.totalRevenue) }}</p>
                    </div>

                    <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">
                            Tautan
                        </p>
                        <p class="text-2xl font-bold text-on-surface">
                            {{ h.totalLinks ?? 0 }}
                            <span v-if="h.activeLinks != null" class="text-sm font-medium text-on-surface-variant"> ({{ h.activeLinks }} aktif)</span>
                        </p>
                    </div>

                    <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">
                            Kunjungan 7 Hari
                        </p>
                        <p class="text-2xl font-bold text-primary">{{ h.peakVisit }}</p>
                        <p class="text-[10px] text-on-surface-variant">Puncak kunjungan harian</p>
                    </div>
                </div>
            </section>

            <!-- Visit Trend Chart -->
            <section aria-labelledby="visit-trend-heading">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h2 id="visit-trend-heading" class="font-bold text-base text-on-surface">Tren Kunjungan (7 Hari)</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5">
                    <div v-if="h.visitTrend?.length" class="flex items-end gap-2 h-32" role="img" :aria-label="'Grafik tren kunjungan 7 hari terakhir. Data: ' + h.visitTrend.map(d => d.date + ': ' + d.count + ' kunjungan').join(', ')">
                        <div
                            v-for="(day, i) in h.visitTrend"
                            :key="day.date"
                            class="flex-1 flex flex-col items-center justify-end h-full"
                        >
                            <span class="text-[10px] font-semibold text-primary mb-1" aria-hidden="true">{{ day.count }}</span>
                            <div
                                class="w-full rounded-t-md transition-all duration-500"
                                :class="i === h.visitTrend.length - 1 ? 'bg-primary' : 'bg-primary/40'"
                                :style="{ height: (day.count / barMax) * 100 + '%', minHeight: day.count > 0 ? '4px' : '0' }"
                            />
                            <span class="text-[9px] text-on-surface-variant mt-1 truncate w-full text-center" aria-hidden="true">{{ formatDate(day.date) }}</span>
                        </div>
                    </div>
                    <div v-else class="text-center py-8 text-on-surface-variant">
                        <p class="text-sm font-medium">Belum ada data kunjungan</p>
                        <p class="text-xs mt-0.5">Kunjungan akan muncul setelah pelanggan membuka halaman publik Anda.</p>
                    </div>
                </div>
            </section>

            <!-- Checkout Trend Chart -->
            <section aria-labelledby="checkout-trend-heading">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h2 id="checkout-trend-heading" class="font-bold text-base text-on-surface">Tren Checkout (7 Hari)</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5">
                    <div v-if="h.checkoutTrend?.length" class="flex items-end gap-2 h-32" role="img" :aria-label="'Grafik tren checkout 7 hari terakhir. Data: ' + h.checkoutTrend.map(d => d.date + ': ' + d.count + ' checkout').join(', ')">
                        <div
                            v-for="(day, i) in h.checkoutTrend"
                            :key="'co-' + day.date"
                            class="flex-1 flex flex-col items-center justify-end h-full"
                        >
                            <span class="text-[10px] font-semibold text-primary mb-1" aria-hidden="true">{{ day.count }}</span>
                            <div
                                class="w-full rounded-t-md transition-all duration-500"
                                :class="i === h.checkoutTrend.length - 1 ? 'bg-emerald-500' : 'bg-emerald-500/40'"
                                :style="{ height: (day.count / checkoutBarMax) * 100 + '%', minHeight: day.count > 0 ? '4px' : '0' }"
                            />
                            <span class="text-[9px] text-on-surface-variant mt-1 truncate w-full text-center" aria-hidden="true">{{ formatDate(day.date) }}</span>
                        </div>
                    </div>
                    <div v-else class="text-center py-8 text-on-surface-variant">
                        <p class="text-sm font-medium">Belum ada data checkout</p>
                        <p class="text-xs mt-0.5">Checkout akan muncul setelah pelanggan melakukan pemesanan.</p>
                    </div>
                </div>
            </section>

            <!-- Product Performance -->
            <section aria-labelledby="product-perf-heading">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h2 id="product-perf-heading" class="font-bold text-base text-on-surface">Performa Produk</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface overflow-hidden">
                    <div v-if="hasProducts" role="table" aria-label="Daftar performa produk">
                        <div class="hidden sm:grid grid-cols-12 gap-3 px-5 py-3 bg-surface-container-low border-b border-outline text-xs font-semibold text-on-surface-variant uppercase tracking-wider" role="row">
                            <div class="col-span-5" role="columnheader">Produk</div>
                            <div class="col-span-3 text-center" role="columnheader">Harga</div>
                            <div class="col-span-2 text-center" role="columnheader">Checkout</div>
                            <div class="col-span-2 text-center" role="columnheader">Status</div>
                        </div>
                        <div
                            v-for="(p, i) in h.productStats"
                            :key="p.id"
                            class="grid grid-cols-2 sm:grid-cols-12 gap-2 sm:gap-3 px-5 py-3.5 border-b border-outline last:border-b-0 items-center text-sm"
                            :class="i % 2 === 0 ? 'bg-surface' : 'bg-surface-container-low'"
                            role="row"
                        >
                            <div class="col-span-2 sm:col-span-5 font-semibold text-on-surface truncate" role="cell">{{ p.name }}</div>
                            <div class="col-span-2 sm:col-span-3 sm:text-center text-on-surface-variant" role="cell">{{ formatRp(p.price) }}</div>
                            <div class="col-span-2 sm:col-span-2 sm:text-center" role="cell">
                                <span class="font-bold" :class="p.checkouts_count > 0 ? 'text-primary' : 'text-on-surface-variant'">
                                    {{ p.checkouts_count }}
                                </span>
                            </div>
                            <div class="col-span-2 sm:col-span-2 sm:text-center" role="cell">
                                <span
                                    class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border"
                                    :class="p.is_active
                                        ? 'border-primary text-primary'
                                        : 'border-outline text-on-surface-variant'"
                                >
                                    {{ p.is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-10 text-on-surface-variant">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada produk</p>
                        <p class="text-xs mt-0.5">Tambahkan produk untuk melihat performa.</p>
                        <Link
                            :href="route('products.create')"
                            class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl bg-primary text-on-primary text-sm font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Produk
                        </Link>
                    </div>
                </div>
            </section>

            <!-- Recent Checkouts -->
            <section aria-labelledby="recent-checkouts-heading">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h2 id="recent-checkouts-heading" class="font-bold text-base text-on-surface">Checkout Terbaru</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface overflow-hidden">
                    <div v-if="hasCheckouts" role="table" aria-label="Daftar checkout terbaru">
                        <div class="hidden sm:grid grid-cols-12 gap-3 px-5 py-3 bg-surface-container-low border-b border-outline text-xs font-semibold text-on-surface-variant uppercase tracking-wider" role="row">
                            <div class="col-span-3" role="columnheader">Produk</div>
                            <div class="col-span-2" role="columnheader">ID Pesanan</div>
                            <div class="col-span-2" role="columnheader">Pembeli</div>
                            <div class="col-span-1 text-center" role="columnheader">Qty</div>
                            <div class="col-span-2 text-center" role="columnheader">Total</div>
                            <div class="col-span-2 text-center" role="columnheader">Status</div>
                        </div>
                        <div
                            v-for="(c, i) in h.recentCheckouts"
                            :key="c.id"
                            class="grid grid-cols-2 sm:grid-cols-12 gap-2 sm:gap-3 px-5 py-3.5 border-b border-outline last:border-b-0 items-center text-sm"
                            :class="i % 2 === 0 ? 'bg-surface' : 'bg-surface-container-low'"
                            role="row"
                        >
                            <div class="col-span-2 sm:col-span-3 font-semibold text-on-surface truncate" role="cell">{{ c.product_name }}</div>
                            <div class="col-span-2 sm:col-span-2 font-mono text-xs text-on-surface-variant truncate" role="cell">{{ c.order_code || '-' }}</div>
                            <div class="col-span-2 sm:col-span-2 text-on-surface-variant truncate" role="cell">{{ c.buyer_name }}</div>
                            <div class="col-span-1 sm:col-span-1 sm:text-center text-on-surface-variant" role="cell">{{ c.quantity }}</div>
                            <div class="col-span-1 sm:col-span-2 sm:text-center text-on-surface font-medium" role="cell">{{ formatRp(c.total_price) }}</div>
                            <div class="col-span-2 sm:col-span-2 sm:text-center" role="cell">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border"
                                    :class="c.status === 'confirmed' || c.status === 'completed' ? 'border-emerald-500 text-emerald-500' :
                                            c.status === 'cancelled' ? 'border-red-400 text-red-400' :
                                            'border-primary/50 text-primary'"
                                >
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="c.status === 'confirmed' || c.status === 'completed' ? 'bg-emerald-500' :
                                                c.status === 'cancelled' ? 'bg-red-400' :
                                                'bg-primary'"
                                    />
                                    {{ c.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-10 text-on-surface-variant">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada checkout</p>
                        <p class="text-xs mt-0.5">Checkout akan muncul setelah pelanggan memesan produk.</p>
                    </div>
                </div>
            </section>

        </div>
    </DashboardLayout>
</template>
