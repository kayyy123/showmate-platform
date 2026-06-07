<script setup>
import { Head } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import BottomNavBar from '@/Components/Shared/BottomNavBar.vue';
</script>

<template>
    <Head title="Dashboard" />

    <div class="min-h-screen bg-surface text-on-surface">
        <AppHeader headline="Dashboard" />

        <main class="pt-20 pb-24 px-4 max-w-[420px] mx-auto">
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-accent/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="font-header text-xl text-on-surface mb-2">Selamat Datang</h2>
                <p class="text-on-surface-variant text-sm">Gunakan navigasi bawah untuk mengelola katalog Anda.</p>
            </div>
        </main>

        <BottomNavBar active="manage" />
    </div>
</template>
