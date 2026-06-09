<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { confirmDelete } from '@/Composables/useConfirm.js';

const page = usePage();

const searchQuery = ref('');
const selectedCategories = ref([]);
const selectedTags = ref([]);
const minPrice = ref('');
const maxPrice = ref('');

function normalizeCode(str) {
    return str ? str.toLowerCase().replace(/[\s-]/g, '') : '';
}

function formatPriceShort(val) {
    return 'Rp ' + Number(val).toLocaleString('id-ID');
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

const priceError = computed(() => {
    if (minPrice.value !== '' && maxPrice.value !== '' && Number(minPrice.value) > Number(maxPrice.value)) {
        return 'Harga minimum tidak boleh lebih besar dari harga maksimum';
    }
    return '';
});

const hasActiveFilters = computed(() => {
    return searchQuery.value || selectedCategories.value.length > 0 || selectedTags.value.length > 0 || minPrice.value || maxPrice.value;
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
    if (!priceError.value) {
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
    }
    return items;
});

function toggleCategory(cat) {
    const idx = selectedCategories.value.indexOf(cat);
    if (idx === -1) {
        selectedCategories.value = [...selectedCategories.value, cat];
    } else {
        selectedCategories.value = selectedCategories.value.filter(c => c !== cat);
    }
}

function toggleTag(tag) {
    const idx = selectedTags.value.indexOf(tag);
    if (idx === -1) {
        selectedTags.value = [...selectedTags.value, tag];
    } else {
        selectedTags.value = selectedTags.value.filter(t => t !== tag);
    }
}

function removeFilter(type) {
    if (type === 'search') searchQuery.value = '';
    else if (type === 'category') selectedCategories.value = [];
    else if (type === 'tag') selectedTags.value = [];
    else if (type === 'minPrice') minPrice.value = '';
    else if (type === 'maxPrice') maxPrice.value = '';
}

function resetFilters() {
    searchQuery.value = '';
    selectedCategories.value = [];
    selectedTags.value = [];
    minPrice.value = '';
    maxPrice.value = '';
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

        <!-- Add Product & Search -->
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
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari nama atau kode produk..."
                aria-label="Cari nama atau kode produk"
                class="flex-1 py-3 px-4 rounded-xl border border-outline bg-surface text-on-surface placeholder-on-surface-variant text-sm focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/30 focus-visible:border-primary transition-all"
            />
        </div>

        <!-- Category, Tag & Price Filters -->
        <div v-if="categories.length > 0 || tags.length > 0" class="space-y-3 mb-4">
            <div v-if="categories.length > 0">
                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Kategori</label>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        @click="toggleCategory(cat)"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        :class="selectedCategories.includes(cat)
                            ? 'bg-primary text-on-primary'
                            : 'border border-outline text-on-surface-variant hover:bg-surface-container'"
                        :aria-pressed="selectedCategories.includes(cat)"
                    >
                        {{ cat }}
                    </button>
                </div>
            </div>

            <div v-if="tags.length > 0">
                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Tag</label>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="tag in tags"
                        :key="tag"
                        @click="toggleTag(tag)"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                        :class="selectedTags.includes(tag)
                            ? 'bg-primary text-on-primary'
                            : 'border border-outline text-on-surface-variant hover:bg-surface-container'"
                        :aria-pressed="selectedTags.includes(tag)"
                    >
                        {{ tag }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5" for="filter-min-price">Harga Minimum</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant pointer-events-none font-mono" aria-hidden="true">Rp</span>
                        <input
                            id="filter-min-price"
                            v-model="minPrice"
                            type="number"
                            min="0"
                            placeholder="Rp 0"
                            aria-label="Harga minimum"
                            class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        />
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5" for="filter-max-price">Harga Maksimum</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant pointer-events-none font-mono" aria-hidden="true">Rp</span>
                        <input
                            id="filter-max-price"
                            v-model="maxPrice"
                            type="number"
                            min="0"
                            placeholder="Rp 100.000"
                            aria-label="Harga maksimum"
                            class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        />
                    </div>
                </div>
            </div>

            <p v-if="priceError" class="text-sm text-red-500 flex items-center gap-1.5" role="alert">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ priceError }}
            </p>

            <!-- Active filter chips -->
            <div v-if="hasActiveFilters" class="flex flex-wrap gap-1.5">
                <span
                    v-if="searchQuery"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-primary/10 text-primary"
                >
                    {{ searchQuery }}
                    <button @click="removeFilter('search')" class="hover:text-primary/70 focus-visible:outline-none" aria-label="Hapus pencarian">×</button>
                </span>
                <span
                    v-for="cat in selectedCategories"
                    :key="'cat-'+cat"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-primary/10 text-primary"
                >
                    {{ cat }}
                    <button @click="toggleCategory(cat)" class="hover:text-primary/70 focus-visible:outline-none" aria-label="Hapus kategori">×</button>
                </span>
                <span
                    v-for="tag in selectedTags"
                    :key="'tag-'+tag"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-primary/10 text-primary"
                >
                    {{ tag }}
                    <button @click="toggleTag(tag)" class="hover:text-primary/70 focus-visible:outline-none" aria-label="Hapus tag">×</button>
                </span>
                <span
                    v-if="minPrice"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-primary/10 text-primary"
                >
                    Min: {{ formatPriceShort(minPrice) }}
                    <button @click="removeFilter('minPrice')" class="hover:text-primary/70 focus-visible:outline-none" aria-label="Hapus harga minimum">×</button>
                </span>
                <span
                    v-if="maxPrice"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-primary/10 text-primary"
                >
                    Max: {{ formatPriceShort(maxPrice) }}
                    <button @click="removeFilter('maxPrice')" class="hover:text-primary/70 focus-visible:outline-none" aria-label="Hapus harga maksimum">×</button>
                </span>
                <button
                    @click="resetFilters"
                    class="text-[11px] font-semibold text-primary hover:text-primary/80 transition-colors focus-visible:outline-none px-2 py-0.5"
                >
                    Reset
                </button>
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
