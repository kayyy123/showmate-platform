<script setup>
import { ref, computed, nextTick } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { confirmDelete } from '@/Composables/useConfirm.js';

const page = usePage();

const searchQuery = ref('');
const filterStatus = ref('all');
const selectedCategories = ref([]);
const selectedTags = ref([]);
const minPrice = ref('');
const maxPrice = ref('');

const showFilterPanel = ref(false);
const filterModalRef = ref(null);
const tempCategories = ref([]);
const tempTags = ref([]);
const tempMinPrice = ref('');
const tempMaxPrice = ref('');
const tempFilterStatus = ref('all');

function normalizeCode(str) {
    return str ? str.toLowerCase().replace(/[\s-]/g, '') : '';
}

const props = defineProps({
    products: Array,
});

const stats = computed(() => {
    const total = props.products.length;
    const active = props.products.filter(p => p.is_active).length;
    const categories = new Set(props.products.map(p => p.category).filter(Boolean)).size;
    return { total, active, inactive: total - active, categories };
});

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

const activeFilterCount = computed(() => {
    let count = 0;
    if (selectedCategories.value.length > 0) count += selectedCategories.value.length;
    if (selectedTags.value.length > 0) count += selectedTags.value.length;
    if (minPrice.value) count++;
    if (maxPrice.value) count++;
    if (filterStatus.value !== 'all') count++;
    return count;
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

function openFilterPanel() {
    tempCategories.value = [...selectedCategories.value];
    tempTags.value = [...selectedTags.value];
    tempMinPrice.value = minPrice.value;
    tempMaxPrice.value = maxPrice.value;
    tempFilterStatus.value = filterStatus.value;
    showFilterPanel.value = true;
    nextTick(() => {
        filterModalRef.value?.focus();
    });
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
    selectedCategories.value = [];
    selectedTags.value = [];
    minPrice.value = '';
    maxPrice.value = '';
    filterStatus.value = 'all';
    showFilterPanel.value = false;
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

async function destroyProduct(product) {
    const confirmed = await confirmDelete();
    if (confirmed) {
        router.delete(route('products.destroy', product.id));
    }
}

function toggleVisibility(product) {
    router.post(route('products.toggle-visibility', product.id), {}, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout activeTab="manage">
        <template #header>Dashboard</template>

        <!-- Stat Cards -->
        <section aria-labelledby="stats-heading">
            <h2 id="stats-heading" class="sr-only">Statistik</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Produk</p>
                </div>
                <p class="text-2xl font-bold text-on-surface">{{ stats.total }}</p>
            </div>

            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kategori</p>
                </div>
                <p class="text-2xl font-bold text-on-surface">{{ stats.categories }}</p>
            </div>

            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Aktif</p>
                </div>
                <p class="text-2xl font-bold text-primary">{{ stats.active }}</p>
            </div>

            <div class="bg-surface-container-low border border-outline rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nonaktif</p>
                </div>
                <p class="text-2xl font-bold text-on-surface">{{ stats.inactive }}</p>
            </div>
        </div>
        </section>

        <!-- Search & Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <Link
                :href="route('products.create')"
                class="inline-flex items-center justify-center gap-2 bg-primary text-on-primary py-3 px-5 rounded-xl font-bold text-sm hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/30"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk
            </Link>
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari nama atau kode produk..."
                    aria-label="Cari nama atau kode produk"
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-outline bg-surface text-on-surface placeholder-on-surface-variant text-sm focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/30 focus-visible:border-primary transition-all"
                />
            </div>
            <button
                @click="openFilterPanel"
                class="shrink-0 inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl border text-sm font-medium transition-all focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/30"
                :class="activeFilterCount
                    ? 'border-primary bg-primary/10 text-primary'
                    : 'border-outline text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
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

        <!-- Filter Modal -->
        <div
            v-if="showFilterPanel"
            class="fixed inset-0 z-50 flex items-center justify-center"
            @keydown.escape.prevent="closeFilterPanel"
            @click.self="closeFilterPanel"
        >
            <div class="fixed inset-0 bg-black/60" aria-hidden="true"></div>
            <div
                ref="filterModalRef"
                class="relative z-10 w-full max-w-md mx-4 rounded-2xl border-2 border-primary bg-surface p-6 shadow-2xl space-y-5"
                role="dialog"
                aria-modal="true"
                aria-label="Filter Produk"
                tabindex="-1"
            >
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-on-surface">Filter Produk</h2>
                    <button
                        @click="closeFilterPanel"
                        class="p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        aria-label="Tutup"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div v-if="categories.length > 0" class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kategori</label>
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

                <div v-if="tags.length > 0" class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tag</label>
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

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</label>
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

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Harga</label>
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

        <!-- Product List -->
        <section>
            <h3 class="font-bold text-lg text-on-surface mb-4">Produk</h3>

            <div v-if="products.length === 0" class="text-center py-16 text-on-surface-variant">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="font-medium">Belum ada produk</p>
                <p class="text-sm mt-1">Mulai dengan menambahkan produk pertama Anda.</p>
            </div>

            <div v-else-if="filteredProducts.length === 0" class="text-center py-16 text-on-surface-variant">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="font-medium">Produk tidak ditemukan</p>
            </div>

            <div v-else class="grid gap-3 md:grid-cols-2">
                <div
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="bg-surface-container-low p-4 rounded-xl border border-outline flex gap-3 hover:border-primary transition-colors group"
                >
                    <div class="w-16 h-16 shrink-0 overflow-hidden rounded-lg">
                        <img
                            v-if="product.image_url"
                            :src="product.image_url"
                            :alt="product.alt_text || product.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                        <div
                            v-else
                            class="w-full h-full bg-surface-container-high flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <h4 class="text-sm font-bold text-on-surface truncate">{{ product.name }}</h4>
                            <span
                                v-if="product.tag"
                                class="text-[10px] px-1.5 py-0.5 rounded border border-primary text-primary font-bold uppercase shrink-0 leading-none"
                            >
                                {{ product.tag }}
                            </span>
                        </div>
                        <p v-if="product.category" class="text-xs text-on-surface-variant mt-0.5">{{ product.category }}</p>
                        <p class="text-primary font-bold text-sm mt-0.5">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                        <span
                            class="inline-block mt-1 text-[10px] px-1.5 py-0.5 font-bold uppercase rounded border leading-none"
                            :class="product.is_active ? 'border-primary text-primary' : 'border-on-surface-variant text-on-surface-variant'"
                        >
                            {{ product.is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>

                        <div class="flex items-center gap-1.5 mt-1.5">
                            <button
                                @click="toggleVisibility(product)"
                                class="text-[10px] px-2 py-1 rounded-lg border font-medium transition-colors"
                                :class="product.is_active ? 'border-outline text-on-surface-variant hover:bg-surface-container' : 'border-primary text-primary hover:brightness-110'"
                            >
                                {{ product.is_active ? 'Sembunyikan' : 'Tampilkan' }}
                            </button>
                            <Link
                                :href="route('products.edit', product.id)"
                                class="text-[10px] px-2 py-1 rounded-lg border border-outline text-on-surface-variant font-medium hover:bg-surface-container transition-colors"
                            >
                                Edit
                            </Link>
                            <button
                                @click="destroyProduct(product)"
                                class="text-[10px] px-2 py-1 rounded-lg border border-red-400/30 text-red-400 font-medium hover:bg-red-400/10 transition-colors"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
