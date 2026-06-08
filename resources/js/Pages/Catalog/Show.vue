<script setup>
import { computed, ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import CatalogHeader from '@/Components/CatalogHeader.vue';
import ProductLinkCard from '@/Components/ProductLinkCard.vue';
import AppHeader from '@/Components/AppHeader.vue';
import BottomNavBar from '@/Components/Shared/BottomNavBar.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    merchant: Object,
    products: Array,
    links: Array,
});

const page = usePage();

const channels = computed(() => {
    const items = [];
    const m = props.merchant;
    if (m.whatsapp_number) items.push({ name: 'WhatsApp', url: `https://wa.me/${m.whatsapp_number}`, icon: 'whatsapp', label: `Hubungi via WhatsApp ${m.whatsapp_number}` });
    if (m.instagram_url) items.push({ name: 'Instagram', url: m.instagram_url, icon: 'instagram', label: `Buka Instagram ${m.name}` });
    if (m.tiktok_url) items.push({ name: 'TikTok', url: m.tiktok_url, icon: 'tiktok', label: `Buka TikTok ${m.name}` });
    if (m.shopee_url) items.push({ name: 'Shopee', url: m.shopee_url, icon: 'shopee', label: `Buka Shopee ${m.name}` });
    if (m.tokopedia_url) items.push({ name: 'Tokopedia', url: m.tokopedia_url, icon: 'tokopedia', label: `Buka Tokopedia ${m.name}` });
    return items;
});

const catalogUrl = computed(() => {
    if (typeof window === 'undefined') return '';
    return window.location.pathname;
});

const showModal = ref(false);
const selectedProduct = ref(null);

const form = useForm({
    product_id: null,
    buyer_name: '',
    buyer_phone: '',
    notes: '',
    quantity: 1,
});

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
    const url = window.location.href;
    const text = 'Lihat katalog ' + props.merchant.name + ': ' + url;
    if (navigator.share) {
        navigator.share({
            title: 'Katalog ' + props.merchant.name,
            text: text,
            url: url,
        }).catch(() => {});
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
    }
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
    <Head :title="merchant.name + ' - Katalog'" />

    <div class="min-h-screen bg-surface text-on-surface">
        <AppHeader headline="Katalog" :showStoreMenu="false" />

        <main class="pt-20 pb-24 px-4 max-w-[480px] mx-auto">
            <CatalogHeader
                :storeName="merchant.name"
                :storeDescription="merchant.description"
                :storeLogo="merchant.store_logo_url"
            />

            <div v-if="channels.length" class="flex flex-wrap gap-2 justify-center mb-8">
                <a
                    v-for="channel in channels"
                    :key="channel.name"
                    :href="channel.url"
                    target="_blank"
                    rel="noopener noreferrer"
                    :aria-label="channel.label"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-outline text-sm font-medium text-on-surface hover:bg-surface-container focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent transition-colors"
                >
                    <svg v-if="channel.icon === 'whatsapp'" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                        <path d="M16 11.5h.01M12 11.5h.01M8 11.5h.01"/>
                    </svg>
                    <svg v-else-if="channel.icon === 'instagram'" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="5"/>
                        <circle cx="12" cy="12" r="5"/>
                        <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                    </svg>
                    <svg v-else-if="channel.icon === 'tiktok'" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18V5l12-2v13"/>
                        <circle cx="6" cy="18" r="3"/>
                        <circle cx="18" cy="16" r="3"/>
                    </svg>
                    <svg v-else-if="channel.icon === 'shopee'" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <svg v-else-if="channel.icon === 'tokopedia'" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                    </svg>
                    <span>{{ channel.name }}</span>
                </a>
            </div>

            <!-- Dynamic Links -->
            <div v-if="links && links.length" class="grid gap-3 mb-8">
                <a
                    v-for="link in links"
                    :key="link.id"
                    :href="link.url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center gap-3 p-4 rounded-2xl border border-outline bg-surface hover:bg-surface-container hover:border-primary/50 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                >
                    <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-on-surface">{{ link.title }}</p>
                        <p class="text-xs text-on-surface-variant truncate">{{ link.url }}</p>
                    </div>
                    <svg class="w-5 h-5 text-on-surface-variant shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <div v-if="products.length === 0" class="text-center py-16">
                <svg class="w-16 h-16 mx-auto mb-4 text-on-surface-variant opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-on-surface-variant font-medium">Belum ada produk tersedia.</p>
                <p class="text-sm text-on-surface-variant mt-1">Katalog belum memiliki produk aktif.</p>
            </div>

            <div v-else class="grid gap-4">
                <ProductLinkCard
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                    @purchase="openCheckout"
                    @inquiry="openCheckout"
                />
            </div>
        </main>

        <!-- FAB Share -->
        <button
            @click="shareCatalog"
            class="fixed bottom-24 right-4 z-40 w-14 h-14 rounded-full bg-accent text-on-accent shadow-xl flex items-center justify-center hover:brightness-110 active:scale-95 transition-all focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-accent/30"
            aria-label="Bagikan katalog"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
        </button>

        <BottomNavBar active="catalog" :catalogUrl="catalogUrl" />

        <Modal :show="showModal" @close="closeModal" max-width="sm">
            <div class="bg-surface p-6 rounded-2xl">
                <h2 class="text-lg font-bold text-on-surface mb-4">Checkout</h2>

                <div v-if="selectedProduct" class="mb-4 p-3 rounded-xl bg-surface-container-high">
                    <p class="font-semibold text-on-surface">{{ selectedProduct.name }}</p>
                    <p class="text-sm text-on-surface-variant mt-0.5">
                        Rp {{ Number(selectedProduct.price).toLocaleString('id-ID') }} /pcs
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
                            class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
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
                            class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
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
                            class="w-full px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors resize-none"
                        ></textarea>
                        <p v-if="form.errors.notes" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.notes }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-1" for="quantity">Quantity</label>
                        <input
                            id="quantity"
                            v-model="form.quantity"
                            type="number"
                            min="1"
                            required
                            class="w-24 px-3 py-2 rounded-lg bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                        />
                        <p v-if="form.errors.quantity" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.quantity }}</p>
                    </div>

                    <div v-if="form.errors.product_id" class="text-sm text-red-400" role="alert">{{ form.errors.product_id }}</div>

                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="flex-1 px-4 py-2 rounded-lg border border-outline text-on-surface font-medium hover:bg-surface-container-high transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 px-4 py-2 rounded-lg bg-accent text-on-accent font-bold hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-50"
                        >
                            {{ form.processing ? 'Memproses...' : 'Kirim ke WhatsApp' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>
