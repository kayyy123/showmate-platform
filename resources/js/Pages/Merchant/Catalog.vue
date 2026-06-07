<script setup>
import { ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import BottomNavBar from '@/Components/Shared/BottomNavBar.vue';

const page = usePage();
const catalogUrl = `/catalog/${page.props.auth.user.slug}`;
const fullCatalogUrl = window.location.origin + catalogUrl;

const copied = ref(false);
const toast = ref(null);
let toastTimer = null;

function showToast(message) {
    toast.value = message;
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { toast.value = null; }, 2500);
}

function copyLink() {
    navigator.clipboard.writeText(fullCatalogUrl);
    copied.value = true;
    showToast('Link katalog berhasil disalin');
    setTimeout(() => { copied.value = false; }, 2000);
}

function shareCatalog() {
    const text = 'Lihat katalog saya: ' + fullCatalogUrl;
    if (navigator.share) {
        navigator.share({
            title: 'Katalog ' + (page.props.auth.user.store_name || page.props.auth.user.name),
            text: text,
            url: fullCatalogUrl,
        }).catch(() => {});
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
    }
}
</script>

<template>
    <Head title="Katalog" />

    <div class="min-h-screen bg-surface text-on-surface">
        <AppHeader headline="Katalog Saya" />

        <main class="pt-20 pb-24 px-4 max-w-[420px] mx-auto">
            <div class="bg-surface-container-low border border-accent/30 rounded-xl p-4 mb-4">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <p class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Link Katalog Saya</p>
                </div>
                <div class="flex items-center gap-2">
                    <input
                        :value="fullCatalogUrl"
                        readonly
                        class="flex-1 px-3 py-2.5 rounded-lg bg-surface-container-high border border-outline text-on-surface text-sm truncate focus:outline-none"
                    />
                    <button
                        @click="copyLink"
                        class="shrink-0 px-3 py-2.5 rounded-lg bg-accent text-on-accent font-bold text-sm hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/50"
                        title="Salin link"
                        aria-label="Salin link katalog"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <button
                        @click="shareCatalog"
                        class="shrink-0 px-3 py-2.5 rounded-lg bg-accent text-on-accent font-bold text-sm hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/50"
                        title="Bagikan katalog"
                        aria-label="Bagikan katalog"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                    </button>
                </div>
            </div>

            <Link
                :href="catalogUrl"
                target="_blank"
                class="w-full flex items-center justify-center gap-2 bg-accent text-on-accent py-3 px-4 rounded-xl font-bold text-sm hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-accent/30"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Buka Katalog
            </Link>

            <!-- Toast -->
            <Teleport to="body">
                <Transition
                    enter-active-class="transition-all duration-300"
                    enter-from-class="opacity-0 translate-y-4"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-200"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 translate-y-4"
                >
                    <div
                        v-if="toast"
                        role="status"
                        aria-live="polite"
                        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface font-medium text-sm shadow-xl"
                    >
                        {{ toast }}
                    </div>
                </Transition>
            </Teleport>
        </main>

        <BottomNavBar active="catalog" />
    </div>
</template>
