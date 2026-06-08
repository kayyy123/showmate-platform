<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

function activatePro() {
    router.post(route('upgrade-pro.upgrade'));
}
</script>

<template>
    <Head title="Upgrade ke Pro" />

    <DashboardLayout activeTab="manage">
        <template #header>Upgrade ke Pro</template>

        <div class="max-w-2xl mx-auto">
            <div class="rounded-2xl border border-yellow-500/40 bg-yellow-500/5 p-6 md:p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-yellow-500/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>

                <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface mb-2">
                    Paket Pro
                </h1>
                <p class="text-on-surface-variant text-sm mb-4">
                    Nikmati semua fitur EtalaseKu tanpa batas.
                </p>

                <div class="mb-6">
                    <span class="text-4xl font-extrabold text-on-surface">Rp 49.000</span>
                    <span class="text-sm text-on-surface-variant"> /bulan</span>
                </div>

                <ul class="space-y-3 mb-8 text-left max-w-sm mx-auto">
                    <li v-for="feature in [
                        'Katalog produk tidak terbatas',
                        'Tautan bisnis tidak terbatas',
                        'Profil toko premium',
                        'Statistik lengkap',
                        'Aksesibilitas penuh',
                        'Tema kustom',
                        'Prioritas dukungan',
                        'Hapus branding EtalaseKu',
                    ]" :key="feature" class="flex items-start gap-3 text-sm">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-on-surface">{{ feature }}</span>
                    </li>
                </ul>

                <button
                    @click="activatePro"
                    class="w-full px-6 py-3 rounded-xl bg-yellow-500 text-black font-bold text-sm hover:bg-yellow-400 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-500/50"
                >
                    Aktifkan Pro Demo
                </button>

                <p class="text-xs text-on-surface-variant mt-4">
                    Demo mode — belum ada pembayaran. Downgrade kapan saja melalui pengaturan akun.
                </p>

                <Link
                    :href="route('merchant.manage')"
                    class="inline-block mt-4 text-sm text-primary hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 rounded px-1"
                >
                    Kembali ke Dashboard
                </Link>
            </div>
        </div>
    </DashboardLayout>
</template>
