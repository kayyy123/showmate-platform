<script setup>
import { computed, ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { useAccessibility } from '@/Composables/useAccessibility';

const { fontSize, highContrast, increaseFont, decreaseFont, resetFont, toggleHighContrast } = useAccessibility();

const props = defineProps({
    merchant: Object,
    products: Array,
    links: Array,
});

const page = usePage();
const showModal = ref(false);
const selectedProduct = ref(null);
const shared = ref(false);
const catalogUrl = typeof window !== 'undefined' ? window.location.href : '';

const channels = computed(() => {
    const items = [];
    const m = props.merchant;
    if (m.whatsapp_number) items.push({ name: 'WhatsApp', url: `https://wa.me/${m.whatsapp_number}`, icon: 'whatsapp', desc: 'Chat via WhatsApp' });
    if (m.instagram_url) items.push({ name: 'Instagram', url: m.instagram_url, icon: 'instagram', desc: 'Ikuti Instagram' });
    if (m.tiktok_url) items.push({ name: 'TikTok', url: m.tiktok_url, icon: 'tiktok', desc: 'Ikuti TikTok' });
    if (m.shopee_url) items.push({ name: 'Shopee', url: m.shopee_url, icon: 'shopee', desc: 'Belanja di Shopee' });
    if (m.tokopedia_url) items.push({ name: 'Tokopedia', url: m.tokopedia_url, icon: 'tokopedia', desc: 'Belanja di Tokopedia' });
    return items;
});

const hasProducts = computed(() => props.products && props.products.length > 0);
const hasLinks = computed(() => props.links && props.links.length > 0);

const form = useForm({
    product_id: null,
    buyer_name: '',
    buyer_phone: '',
    notes: '',
    quantity: 1,
});

function formatPrice(price) {
    return 'Rp ' + Number(price).toLocaleString('id-ID');
}

function openCheckout(product) {
    selectedProduct.value = product;
    form.reset();
    form.product_id = product.id;
    form.quantity = 1;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedProduct.value = null;
    form.clearErrors();
    form.reset();
}

function shareCatalog() {
    const text = 'Lihat katalog ' + props.merchant.name + ': ' + catalogUrl;
    if (navigator.share) {
        navigator.share({
            title: 'Katalog ' + props.merchant.name,
            text,
            url: catalogUrl,
        }).catch(() => {});
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
    }
}

function copyLink() {
    navigator.clipboard.writeText(catalogUrl).then(() => {
        shared.value = true;
        setTimeout(() => { shared.value = false; }, 2000);
    }).catch(() => {});
}

function submitCheckout() {
    form.post(`/catalog/${props.merchant.id}/checkout`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            const waUrl = page.props.flash?.wa_url;
            if (waUrl) {
                window.open(waUrl, '_blank');
            }
        },
    });
}

</script>

<template>
    <Head :title="merchant.name + ' - Katalog UMKM'" />

    <div class="min-h-screen bg-surface text-on-surface">

        <!-- Banner + Profile -->
        <header class="relative overflow-hidden">
            <!-- Decorative banner -->
            <div class="relative h-40 sm:h-48 bg-gradient-to-br from-primary/30 via-secondary/20 to-tertiary/20">
                <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/50 to-transparent" />
            </div>

            <!-- Profile section -->
            <div class="relative -mt-16 px-4 pb-6 text-center">
                <div class="w-28 h-28 mx-auto mb-4 rounded-full border-4 border-surface shadow-xl overflow-hidden bg-surface-container">
                    <img
                        v-if="merchant.store_logo_url"
                        :src="merchant.store_logo_url"
                        :alt="'Logo ' + merchant.name"
                        class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center bg-primary/10" aria-hidden="true">
                        <svg class="w-10 h-10 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-on-surface">{{ merchant.name }}</h1>
                <p v-if="merchant.description" class="mt-2 text-sm text-on-surface-variant max-w-md mx-auto">
                    {{ merchant.description }}
                </p>

                <!-- Share buttons -->
                <div class="flex items-center justify-center gap-2 mt-4">
                    <button
                        @click="shareCatalog"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary/10 text-primary text-sm font-semibold hover:bg-primary/20 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        Bagikan
                    </button>
                    <button
                        @click="copyLink"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-container border border-outline text-on-surface text-sm font-semibold hover:bg-surface-container-high transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        {{ shared ? 'Tersalin!' : 'Salin' }}
                    </button>
                </div>

                <!-- A11y toolbar -->
                <div class="flex items-center justify-center gap-2 mt-3" role="toolbar" aria-label="Pengaturan aksesibilitas">
                    <button
                        @click="decreaseFont"
                        :disabled="fontSize <= 80"
                        class="w-9 h-9 rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors disabled:opacity-30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-label="Perkecil ukuran teks"
                    >
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>
                    <span class="text-xs font-medium text-on-surface-variant w-8 text-center" aria-live="polite">{{ fontSize }}%</span>
                    <button
                        @click="increaseFont"
                        :disabled="fontSize >= 140"
                        class="w-9 h-9 rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors disabled:opacity-30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-label="Perbesar ukuran teks"
                    >
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <span class="w-px h-6 bg-outline mx-1" aria-hidden="true" />
                    <button
                        @click="toggleHighContrast"
                        class="w-9 h-9 rounded-lg border transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        :class="highContrast ? 'bg-primary text-on-primary border-primary' : 'border-outline text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
                        :aria-label="highContrast ? 'Nonaktifkan mode kontras tinggi' : 'Aktifkan mode kontras tinggi'"
                        :aria-pressed="highContrast"
                    >
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    <button
                        v-if="fontSize !== 100"
                        @click="resetFont"
                        class="text-xs font-medium text-primary hover:text-primary/80 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-label="Reset ukuran teks ke default"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </header>

        <main class="px-4 pb-8 max-w-md mx-auto space-y-6">

            <!-- Channel Links -->
            <section v-if="channels.length" aria-labelledby="channels-heading">
                <h2 id="channels-heading" class="sr-only">Tautan Bisnis</h2>
                <div class="space-y-2.5">
                    <a
                        v-for="ch in channels"
                        :key="ch.name"
                        :href="ch.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center gap-4 w-full px-5 py-3.5 rounded-2xl border border-outline bg-surface hover:bg-surface-container hover:border-primary/40 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 group"
                    >
                        <!-- Icon -->
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-transform group-hover:scale-105"
                            :class="ch.icon === 'whatsapp' ? 'bg-green-500/10 text-green-600' :
                                    ch.icon === 'instagram' ? 'bg-pink-500/10 text-pink-600' :
                                    ch.icon === 'tiktok' ? 'bg-gray-900/10 text-gray-900 dark:text-white dark:bg-white/10' :
                                    ch.icon === 'shopee' ? 'bg-orange-500/10 text-orange-600' :
                                    ch.icon === 'tokopedia' ? 'bg-green-600/10 text-green-700' :
                                    'bg-primary/10 text-primary'"
                        >
                            <!-- WhatsApp -->
                            <svg v-if="ch.icon === 'whatsapp'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <!-- Instagram -->
                            <svg v-else-if="ch.icon === 'instagram'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/>
                            </svg>
                            <!-- TikTok -->
                            <svg v-else-if="ch.icon === 'tiktok'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                            <!-- Shopee -->
                            <svg v-else-if="ch.icon === 'shopee'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12.912 2.904c.103.003.206.008.308.017 2.21.18 4.042 1.557 4.904 3.489.327.732.477 1.467.494 2.201.02.852-.126 1.598-.42 2.28-.138.319-.404.576-.597.857a.963.963 0 01-.12.139c-.488.487-1.046.8-1.677.982-.625.18-1.27.23-1.905.107-.554-.107-1.064-.334-1.472-.718-.238-.225-.428-.49-.55-.795a5.27 5.27 0 01-.218-.541c-.146-.43-.457-.657-.892-.66-.528-.003-.905.27-1.067.775l-.003.012c-.145.448-.159.895-.002 1.343.117.334.303.611.59.832.632.487 1.38.699 2.148.714.588.012 1.158-.079 1.715-.278.537-.192 1.043-.457 1.501-.8.144-.108.278-.23.41-.353v.006c.18.111.343.24.483.395.567.627.836 1.366.877 2.218.044.922-.13 1.792-.574 2.58-.375.666-.906 1.18-1.553 1.575-.745.455-1.571.685-2.454.721-.855.035-1.677-.09-2.455-.402-.598-.24-1.116-.59-1.535-1.078-.375-.437-.63-.946-.775-1.518-.158-.625-.127-1.25-.018-1.88.174-1.002.63-1.857 1.3-2.58.664-.718 1.487-1.193 2.44-1.465.07-.02.141-.04.212-.057-.136-.096-.25-.216-.355-.347-.63-.79-.862-1.699-.701-2.699.178-1.106.771-1.98 1.66-2.66.315-.24.667-.418 1.047-.532.33-.1.668-.144 1.01-.137z"/>
                            </svg>
                            <!-- Tokopedia -->
                            <svg v-else-if="ch.icon === 'tokopedia'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M5.763 17.487c.003-.05.003-.1.004-.149.008-.828.012-1.659.022-2.487.005-.397-.023-.656-.377-.88-.663-.421-1.324-.846-1.987-1.267-.36-.228-.542-.547-.54-.99.002-.454.178-.78.545-1.01.658-.411 1.321-.814 1.982-1.22.336-.207.372-.486.37-.842-.011-1.101-.005-2.202-.005-3.303H5.761c-.159 0-.315-.013-.466.024-.728.179-1.312-.08-1.59-.759a1.36 1.36 0 01.136-1.282c.243-.37.6-.542 1.03-.523.422.018.836.14 1.215.330.206.104.32.065.433-.112.187-.292.378-.581.567-.872.004-.006.009-.011.014-.017h5.362c.005.006.01.011.014.017.189.291.38.58.567.872.113.177.227.216.433.112.379-.19.793-.312 1.215-.33.43-.019.787.153 1.03.523.238.361.282.792.136 1.282-.278.68-.862.938-1.59.759-.15-.037-.307-.024-.466-.024h-.002c-.002 0-.004.002-.007.005v4.678c-.003.393-.06.737-.455.97-.672.395-1.354.774-2.031 1.16-.348.2-.54.54-.532.937.01.582.004 1.164.004 1.746v2.594c.002.15.014.298.04.444.097.547.024.905-.48 1.207-.294.176-.607.282-.95.289-.407.008-.73-.132-1.007-.43-.301-.324-.385-.72-.237-1.138a1.611 1.611 0 01.275-.487c.207-.254.198-.537.194-.833-.003-.392-.005-.784.003-1.176.001-.061.008-.122.012-.182z"/>
                            </svg>
                            <!-- Globe fallback -->
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-sm font-semibold text-on-surface">{{ ch.name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ ch.desc }}</p>
                        </div>
                        <svg class="w-5 h-5 text-on-surface-variant shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </section>

            <!-- Dynamic Links -->
            <section v-if="hasLinks" aria-labelledby="custom-links-heading">
                <h2 id="custom-links-heading" class="sr-only">Tautan Tambahan</h2>
                <div class="space-y-2.5">
                    <a
                        v-for="link in links"
                        :key="link.id"
                        :href="link.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center gap-4 w-full px-5 py-3.5 rounded-2xl border border-outline bg-surface hover:bg-surface-container hover:border-primary/40 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 group"
                    >
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-sm font-semibold text-on-surface">{{ link.title }}</p>
                            <p class="text-xs text-on-surface-variant truncate">{{ link.url }}</p>
                        </div>
                        <svg class="w-5 h-5 text-on-surface-variant shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </section>

            <!-- Products -->
            <section v-if="hasProducts" aria-labelledby="products-heading">
                <h2 id="products-heading" class="text-base font-bold text-on-surface mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Katalog Produk
                </h2>
                <div class="space-y-3">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="rounded-2xl border border-outline bg-surface overflow-hidden transition-all hover:border-primary/40 hover:shadow-lg hover:shadow-primary/5"
                    >
                        <div class="flex gap-4 p-4">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 overflow-hidden rounded-xl bg-surface-container-high">
                                <img
                                    v-if="product.image_url"
                                    :src="product.image_url"
                                    :alt="product.alt_text || product.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center" aria-hidden="true">
                                    <svg class="w-6 h-6 text-on-surface-variant/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-sm font-bold text-on-surface leading-tight">{{ product.name }}</h3>
                                        <span
                                            v-if="product.tag"
                                            class="text-[10px] px-1.5 py-0.5 rounded border border-primary text-primary font-bold uppercase shrink-0 leading-none"
                                        >
                                            {{ product.tag }}
                                        </span>
                                    </div>
                                    <p v-if="product.description" class="text-xs text-on-surface-variant mt-0.5 line-clamp-2">
                                        {{ product.description }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-primary font-bold text-sm">{{ formatPrice(product.price) }}</p>
                                    <button
                                        @click="openCheckout(product)"
                                        class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                                        :aria-label="'Beli ' + product.name"
                                    >
                                        Beli
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Empty state (no products) -->
            <section v-if="!hasProducts && !hasLinks && channels.length === 0" class="text-center py-16">
                <svg class="w-16 h-16 mx-auto mb-4 text-on-surface-variant opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-on-surface-variant font-medium">Belum ada konten tersedia.</p>
                <p class="text-sm text-on-surface-variant mt-1">UMKM ini belum menambahkan produk atau tautan.</p>
            </section>

            <!-- Footer -->
            <footer class="text-center pt-4 pb-2">
                <p class="text-xs text-on-surface-variant/60">
                    <span aria-hidden="true">&copy; </span> {{ new Date().getFullYear() }} {{ merchant.name }}
                </p>
            </footer>
        </main>

        <!-- Checkout Modal -->
        <Modal :show="showModal" @close="closeModal" max-width="sm">
            <div class="bg-surface p-6 rounded-2xl">
                <h2 class="text-lg font-bold text-on-surface mb-4" id="checkout-heading">Checkout</h2>

                <div v-if="selectedProduct" class="mb-4 p-3 rounded-xl bg-surface-container-high">
                    <p class="font-semibold text-on-surface">{{ selectedProduct.name }}</p>
                    <p class="text-sm text-on-surface-variant mt-0.5">
                        {{ formatPrice(selectedProduct.price) }}
                    </p>
                </div>

                <form @submit.prevent="submitCheckout" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1" for="buyer_name">Nama Pembeli</label>
                        <input
                            id="buyer_name"
                            v-model="form.buyer_name"
                            type="text"
                            required
                            placeholder="Masukkan nama Anda"
                            class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                        />
                        <p v-if="form.errors.buyer_name" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.buyer_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1" for="buyer_phone">Nomor WhatsApp</label>
                        <input
                            id="buyer_phone"
                            v-model="form.buyer_phone"
                            type="tel"
                            required
                            placeholder="Contoh: 6281234567890"
                            class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                        />
                        <p v-if="form.errors.buyer_phone" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.buyer_phone }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1" for="notes">Alamat / Catatan</label>
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            rows="3"
                            placeholder="Alamat pengiriman atau catatan lainnya"
                            class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none"
                        ></textarea>
                        <p v-if="form.errors.notes" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.notes }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1" for="quantity">Jumlah</label>
                        <input
                            id="quantity"
                            v-model="form.quantity"
                            type="number"
                            min="1"
                            required
                            class="w-24 px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                        />
                        <p v-if="form.errors.quantity" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.quantity }}</p>
                    </div>

                    <div v-if="form.errors.product_id" class="text-sm text-red-400" role="alert">{{ form.errors.product_id }}</div>

                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="flex-1 px-4 py-2 rounded-lg border border-outline text-on-surface font-medium hover:bg-surface-container-high transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 px-4 py-2 rounded-lg bg-primary text-on-primary font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Memproses...' : 'Kirim ke WhatsApp' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>
