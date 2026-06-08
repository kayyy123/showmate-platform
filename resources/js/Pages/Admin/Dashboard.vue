<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
});

const statCards = [
    { label: 'Total Merchant', value: 'merchantCount', icon: 'users' },
    { label: 'Total Produk', value: 'productCount', icon: 'package' },
    { label: 'Total Kunjungan', value: 'visitCount', icon: 'eye' },
    { label: 'Total Checkout', value: 'checkoutCount', icon: 'cart' },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="min-h-screen bg-surface text-on-surface">
        <header class="border-b border-outline bg-surface/80 backdrop-blur sticky top-0 z-20">
            <div class="max-w-6xl mx-auto px-4 md:px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="font-bold text-xl text-on-surface tracking-tight">EtalaseKu</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 text-[10px] font-bold uppercase tracking-wider">Admin</span>
                </div>
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('login')"
                        method="post"
                        as="button"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-red-400 hover:bg-surface-container transition-colors"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-4 md:px-6 py-8">
            <h1 class="text-2xl md:text-3xl font-extrabold mb-8">Dashboard Admin</h1>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <div
                    v-for="card in statCards"
                    :key="card.value"
                    class="rounded-2xl border border-outline bg-surface-container-low p-5"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <svg v-if="card.icon === 'users'" class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <svg v-else-if="card.icon === 'package'" class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <svg v-else-if="card.icon === 'eye'" class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg v-else-if="card.icon === 'cart'" class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">{{ card.label }}</p>
                    </div>
                    <p class="text-3xl font-extrabold text-on-surface">{{ stats[card.value] }}</p>
                </div>
            </div>
        </main>
    </div>
</template>
