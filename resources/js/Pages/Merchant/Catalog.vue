<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { confirmDelete } from '@/Composables/useConfirm.js';
import { showSuccess, showError } from '@/Composables/useToast';

const props = defineProps({
    products: Array,
});

const page = usePage();
const catalogUrl = `/catalog/${page.props.auth.user.store_slug || page.props.auth.user.slug}`;
const fullCatalogUrl = window.location.origin + catalogUrl;

const searchQuery = ref('');
const filterStatus = ref('all');
const selectedCategories = ref([]);
const selectedTags = ref([]);
const minPrice = ref('');
const maxPrice = ref('');

const showFilterPanel = ref(false);
const tempCategories = ref([]);
const tempTags = ref([]);
const tempMinPrice = ref('');
const tempMaxPrice = ref('');
const tempFilterStatus = ref('all');

function normalizeCode(str) {
    return str ? str.toLowerCase().replace(/[\s-]/g, '') : '';
}

const categories = computed(() => {
    if (!props.products) return [];
    const cats = [...new Set(props.products.map(p => p.category).filter(Boolean))];
    return cats.sort();
});

const tags = computed(() => {
    if (!props.products) return [];
    const t = [...new Set(props.products.map(p => p.tag).filter(Boolean))];
    return t.sort();
});

const filteredProducts = computed(() => {
    let items = props.products;
    const q = searchQuery.value.toLowerCase().trim();
    if (q) {
        const qNormalized = normalizeCode(q);
        items = items.filter(p => {
            if (p.name && p.name.toLowerCase().includes(q)) return true;
            if (p.product_code && normalizeCode(p.product_code).includes(qNormalized)) return true;
            if (p.category && p.category.toLowerCase().includes(q)) return true;
            if (p.tag && p.tag.toLowerCase().includes(q)) return true;
            return false;
        });
    }
    if (selectedCategories.value.length > 0) {
        items = items.filter(p => p.category && selectedCategories.value.includes(p.category));
    }
    if (selectedTags.value.length > 0) {
        items = items.filter(p => p.tag && selectedTags.value.includes(p.tag));
    }
    if (filterStatus.value === 'active') {
        items = items.filter(p => p.is_active);
    } else if (filterStatus.value === 'inactive') {
        items = items.filter(p => !p.is_active);
    }
    if (minPrice.value !== '') {
        const min = Number(minPrice.value);
        if (!isNaN(min)) {
            items = items.filter(p => Number(p.price) >= min);
        }
    }
    if (maxPrice.value !== '') {
        const max = Number(maxPrice.value);
        if (!isNaN(max)) {
            items = items.filter(p => Number(p.price) <= max);
        }
    }
    return items;
});

const activeCount = computed(() => props.products.filter(p => p.is_active).length);
const inactiveCount = computed(() => props.products.filter(p => !p.is_active).length);

const activeFilterCount = computed(() => {
    let count = 0;
    if (selectedCategories.value.length > 0) count += selectedCategories.value.length;
    if (selectedTags.value.length > 0) count += selectedTags.value.length;
    if (minPrice.value) count++;
    if (maxPrice.value) count++;
    if (filterStatus.value !== 'all') count++;
    return count;
});

function openFilterPanel() {
    tempCategories.value = [...selectedCategories.value];
    tempTags.value = [...selectedTags.value];
    tempMinPrice.value = minPrice.value;
    tempMaxPrice.value = maxPrice.value;
    tempFilterStatus.value = filterStatus.value;
    showFilterPanel.value = true;
}

function closeFilterPanel() {
    showFilterPanel.value = false;
}

function applyFilters() {
    selectedCategories.value = [...tempCategories.value];
    selectedTags.value = [...tempTags.value];
    minPrice.value = tempMinPrice.value;
    maxPrice.value = tempMaxPrice.value;
    filterStatus.value = tempFilterStatus.value;
    showFilterPanel.value = false;
}

function clearTempFilters() {
    tempCategories.value = [];
    tempTags.value = [];
    tempMinPrice.value = '';
    tempMaxPrice.value = '';
    tempFilterStatus.value = 'all';
}

function toggleTempCategory(cat) {
    const idx = tempCategories.value.indexOf(cat);
    if (idx === -1) {
        tempCategories.value = [...tempCategories.value, cat];
    } else {
        tempCategories.value = tempCategories.value.filter(c => c !== cat);
    }
}

function toggleTempTag(tag) {
    const idx = tempTags.value.indexOf(tag);
    if (idx === -1) {
        tempTags.value = [...tempTags.value, tag];
    } else {
        tempTags.value = tempTags.value.filter(t => t !== tag);
    }
}

function copyLink() {
    if (!navigator.clipboard) {
        const textarea = document.createElement('textarea');
        textarea.value = fullCatalogUrl;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showSuccess('Link katalog berhasil disalin');
        return;
    }
    navigator.clipboard.writeText(fullCatalogUrl).then(() => {
        showSuccess('Link katalog berhasil disalin');
    }).catch(() => {
        showError('Gagal menyalin link');
    });
}

function shareCatalog() {
    const text = 'Lihat katalog saya: ' + fullCatalogUrl;
    if (navigator.share) {
        navigator.share({
            title: 'Katalog ' + (page.props.auth.user.store_name || page.props.auth.user.name),
            text,
            url: fullCatalogUrl,
        }).catch(() => {});
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
    }
}

function toggleVisibility(product) {
    router.post(route('products.toggle-visibility', product.id), {}, {
        preserveState: true,
        preserveScroll: true,
    });
}

async function destroyProduct(product) {
    const confirmed = await confirmDelete();
    if (confirmed) {
        router.delete(route('products.destroy', product.id), {
            preserveScroll: true,
        });
    }
}

function formatPrice(price) {
    return 'Rp ' + Number(price).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Katalog Saya" />

    <DashboardLayout activeTab="catalog">
        <template #header>Katalog Saya</template>

        <div class="space-y-6 max-w-4xl">

            <!-- Catalog Link Card -->
            <div class="rounded-2xl border border-primary/30 bg-primary/5 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <p class="text-sm font-semibold text-on-surface-variant uppercase tracking-wider">Link Katalog Publik</p>
                </div>
                <div class="flex items-center gap-2">
                    <input
                        :value="fullCatalogUrl"
                        readonly
                        class="flex-1 px-3 py-2.5 rounded-lg bg-surface-container-high border border-outline text-on-surface text-sm truncate focus:outline-none"
                        aria-label="URL Katalog Publik"
                    />
                    <button
                        @click="copyLink"
                        class="shrink-0 px-3 py-2.5 rounded-lg bg-primary text-on-primary font-bold text-sm hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-label="Salin link katalog"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <button
                        @click="shareCatalog"
                        class="shrink-0 px-3 py-2.5 rounded-lg bg-primary text-on-primary font-bold text-sm hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-label="Bagikan katalog"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                    </button>
                    <a
                        :href="catalogUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="shrink-0 px-3 py-2.5 rounded-lg border border-primary text-primary font-bold text-sm hover:bg-primary/10 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-label="Buka halaman katalog publik"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Stats & Actions -->
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
                <div class="flex items-center gap-3 text-sm text-on-surface-variant">
                    <span class="font-medium text-on-surface">{{ props.products.length }} Produk</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-outline" />
                    <span class="text-primary font-medium">{{ activeCount }} aktif</span>
                    <span v-if="inactiveCount" class="text-on-surface-variant">{{ inactiveCount }} nonaktif</span>
                </div>
                <Link
                    :href="route('products.create')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk
                </Link>
            </div>

            <!-- Search & Filter -->
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari nama atau kode produk..."
                        aria-label="Cari nama atau kode produk"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline bg-surface text-on-surface placeholder:text-on-surface-variant text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/20 focus-visible:border-primary transition-all"
                    />
                </div>
                <button
                    @click="showFilterPanel ? closeFilterPanel() : openFilterPanel()"
                    class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border text-sm font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                    :class="activeFilterCount
                        ? 'border-primary bg-primary/10 text-primary'
                        : 'border-outline text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
                    :aria-expanded="showFilterPanel"
                    aria-haspopup="true"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Filter
                    <span v-if="activeFilterCount" class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-primary text-[10px] font-bold text-on-primary leading-none">
                        {{ activeFilterCount }}
                    </span>
                </button>
            </div>

            <!-- Filter Panel Dropdown -->
            <div v-if="showFilterPanel" class="relative z-30">
                <div class="w-full max-w-xs ml-auto rounded-2xl border-2 border-primary bg-surface p-5 shadow-2xl space-y-4">
                    <div v-if="categories.length > 0">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Kategori</label>
                        <div class="space-y-1">
                            <label
                                v-for="cat in categories"
                                :key="cat"
                                class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg cursor-pointer hover:bg-surface-container transition-colors"
                            >
                                <input
                                    type="checkbox"
                                    :checked="tempCategories.includes(cat)"
                                    @change="toggleTempCategory(cat)"
                                    class="w-4 h-4 rounded border-outline text-primary focus:ring-primary/30 focus:ring-offset-0"
                                />
                                <span class="text-sm text-on-surface select-none">{{ cat }}</span>
                            </label>
                        </div>
                    </div>

                    <hr class="border-outline/60" />

                    <div v-if="tags.length > 0">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Tag</label>
                        <div class="space-y-1">
                            <label
                                v-for="tag in tags"
                                :key="tag"
                                class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg cursor-pointer hover:bg-surface-container transition-colors"
                            >
                                <input
                                    type="checkbox"
                                    :checked="tempTags.includes(tag)"
                                    @change="toggleTempTag(tag)"
                                    class="w-4 h-4 rounded border-outline text-primary focus:ring-primary/30 focus:ring-offset-0"
                                />
                                <span class="text-sm text-on-surface select-none">{{ tag }}</span>
                            </label>
                        </div>
                    </div>

                    <hr class="border-outline/60" />

                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Status</label>
                        <div class="flex gap-2">
                            <button
                                @click="tempFilterStatus = 'all'"
                                class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                                :class="tempFilterStatus === 'all' ? 'bg-primary text-on-primary' : 'border border-outline text-on-surface-variant hover:bg-surface-container'"
                            >
                                Semua
                            </button>
                            <button
                                @click="tempFilterStatus = 'active'"
                                class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                                :class="tempFilterStatus === 'active' ? 'bg-primary text-on-primary' : 'border border-outline text-on-surface-variant hover:bg-surface-container'"
                            >
                                Aktif
                            </button>
                            <button
                                @click="tempFilterStatus = 'inactive'"
                                class="px-3 py-1.5 rounded-xl text-xs font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                                :class="tempFilterStatus === 'inactive' ? 'bg-primary text-on-primary' : 'border border-outline text-on-surface-variant hover:bg-surface-container'"
                            >
                                Nonaktif
                            </button>
                        </div>
                    </div>

                    <hr class="border-outline/60" />

                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2">Harga</label>
                        <div class="space-y-2">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant pointer-events-none font-mono" aria-hidden="true">Rp</span>
                                <input
                                    v-model="tempMinPrice"
                                    type="number"
                                    min="0"
                                    placeholder="Minimum"
                                    aria-label="Harga minimum"
                                    class="w-full pl-9 pr-3 py-2 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                />
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant pointer-events-none font-mono" aria-hidden="true">Rp</span>
                                <input
                                    v-model="tempMaxPrice"
                                    type="number"
                                    min="0"
                                    placeholder="Maksimum"
                                    aria-label="Harga maksimum"
                                    class="w-full pl-9 pr-3 py-2 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-outline/60">
                        <button
                            @click="clearTempFilters"
                            class="text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors px-3 py-1.5 rounded-lg hover:bg-surface-container"
                        >
                            Bersihkan Filter
                        </button>
                        <button
                            @click="applyFilters"
                            class="px-5 py-1.5 rounded-xl bg-primary text-on-primary text-sm font-bold hover:brightness-110 active:scale-[0.98] transition-all"
                        >
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="props.products.length === 0" class="rounded-2xl border-2 border-dashed border-outline p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-on-surface-variant/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="text-lg font-bold text-on-surface mb-1">Belum ada produk</h3>
                <p class="text-sm text-on-surface-variant mb-4">Mulai dengan menambahkan produk pertama Anda.</p>
                <Link
                    :href="route('products.create')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold hover:brightness-110 active:scale-[0.98] transition-all"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk Baru
                </Link>
            </div>

            <!-- No Results -->
            <div v-else-if="filteredProducts.length === 0" class="text-center py-16 text-on-surface-variant">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="font-medium">Produk tidak ditemukan</p>
                <p class="text-sm mt-1">Tidak ada produk yang cocok dengan filter yang dipilih</p>
            </div>

            <!-- Product Grid -->
            <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="rounded-2xl border border-outline bg-surface overflow-hidden transition-all hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5 group"
                    :class="{ 'opacity-60': !product.is_active }"
                >
                    <!-- Image -->
                    <div class="relative aspect-[4/3] bg-surface-container-low overflow-hidden">
                        <img
                            v-if="product.image_url"
                            :src="product.image_url"
                            :alt="product.alt_text || product.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center"
                        >
                            <svg class="w-10 h-10 text-on-surface-variant/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <!-- Status badge -->
                        <div class="absolute top-2 left-2">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border shadow-sm"
                                :class="product.is_active
                                    ? 'bg-emerald-500/90 text-white border-emerald-500'
                                    : 'bg-surface/90 text-on-surface-variant border-outline'"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full"
                                    :class="product.is_active ? 'bg-white' : 'bg-on-surface-variant'"
                                />
                                {{ product.is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <!-- Tag badge -->
                        <div v-if="product.tag" class="absolute top-2 right-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary/90 text-on-primary shadow-sm">
                                {{ product.tag }}
                            </span>
                        </div>
                        <!-- Hover overlay actions -->
                        <div class="absolute inset-0 bg-on-surface/0 group-hover:bg-on-surface/40 transition-colors flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                            <Link
                                :href="route('products.edit', product.id)"
                                class="w-9 h-9 rounded-full bg-white/90 text-on-surface flex items-center justify-center hover:bg-white transition-all shadow-lg"
                                aria-label="Edit produk"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </Link>
                            <button
                                @click="toggleVisibility(product)"
                                class="w-9 h-9 rounded-full bg-white/90 flex items-center justify-center hover:bg-white transition-all shadow-lg"
                                :class="product.is_active ? 'text-amber-600' : 'text-emerald-600'"
                                :aria-label="product.is_active ? 'Sembunyikan produk' : 'Tampilkan produk'"
                            >
                                <svg v-if="product.is_active" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button
                                @click="destroyProduct(product)"
                                class="w-9 h-9 rounded-full bg-white/90 text-red-500 flex items-center justify-center hover:bg-white transition-all shadow-lg"
                                aria-label="Hapus produk"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-4 space-y-1.5">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-sm font-bold text-on-surface leading-tight line-clamp-2">{{ product.name }}</h3>
                        </div>
                        <p v-if="product.product_code" class="text-[10px] font-mono text-on-surface-variant">Kode: {{ product.product_code }}</p>
                        <p v-if="product.category" class="text-xs text-on-surface-variant">{{ product.category }}</p>
                        <p class="text-primary font-bold text-base">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                        <p v-if="product.description" class="text-xs text-on-surface-variant line-clamp-2">{{ product.description }}</p>
                    </div>
                </div>
            </div>

            <!-- Public Preview Section -->
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <h2 class="font-bold text-lg text-on-surface">Pratinjau Tampilan Publik</h2>
                    </div>
                    <a
                        :href="catalogUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary/80 transition-colors"
                    >
                        Buka halaman
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5">
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-3 text-center">Preview produk di halaman publik</p>
                    <div class="grid gap-3 sm:grid-cols-2 max-w-lg mx-auto">
                        <div
                            v-for="product in filteredProducts.slice(0, 4)"
                            :key="'prev-' + product.id"
                            class="flex gap-3 p-3 rounded-xl border border-outline bg-surface-container-low"
                        >
                            <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0 bg-surface-container-high">
                                <img
                                    v-if="product.image_url"
                                    :src="product.image_url"
                                    :alt="product.alt_text || product.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-on-surface-variant/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-on-surface truncate">{{ product.name }}</p>
                                <p class="text-xs text-primary font-medium">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </DashboardLayout>
</template>
