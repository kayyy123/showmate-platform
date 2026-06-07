<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import BottomNavBar from '@/Components/Shared/BottomNavBar.vue';

const page = usePage();
const catalogUrl = `/catalog/${page.props.auth.user.slug}`;

const props = defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Statistik" />

    <div class="min-h-screen bg-surface text-on-surface">
        <AppHeader :headline="'Statistik ' + stats.userName" />

        <main class="pt-20 pb-24 px-4 max-w-[420px] mx-auto">
            <div class="grid grid-cols-2 gap-3 mb-8">
                <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Produk</p>
                    </div>
                    <p class="text-2xl font-bold text-on-surface">{{ stats.totalProducts }}</p>
                </div>

                <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Aktif</p>
                    </div>
                    <p class="text-2xl font-bold text-accent">{{ stats.activeProducts }}</p>
                </div>

                <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Checkout</p>
                    </div>
                    <p class="text-2xl font-bold text-accent">{{ stats.totalCheckouts }}</p>
                </div>

                <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kunjungan</p>
                    </div>
                    <p class="text-2xl font-bold text-accent">{{ stats.totalVisits }}</p>
                </div>
            </div>
        </main>

        <BottomNavBar active="stats" :catalogUrl="catalogUrl" />
    </div>
</template>
