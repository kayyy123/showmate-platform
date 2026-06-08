<script setup>
import { ref, watch, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const page = usePage();
const toast = ref(null);
let toastTimer = null;

const searchQuery = ref('');

const props = defineProps({
    products: Array,
});

const stats = computed(() => {
    const total = props.products.length;
    const active = props.products.filter(p => p.is_active).length;
    const categories = new Set(props.products.map(p => p.category).filter(Boolean)).size;
    return { total, active, inactive: total - active, categories };
});

const filteredProducts = computed(() => {
    const q = searchQuery.value.toLowerCase().trim();
    if (!q) return props.products;
    return props.products.filter(p =>
        (p.name && p.name.toLowerCase().includes(q)) ||
        (p.category && p.category.toLowerCase().includes(q)) ||
        (p.tag && p.tag.toLowerCase().includes(q))
    );
});

function showToast(message) {
    toast.value = message;
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { toast.value = null; }, 2500);
}

function destroyProduct(product) {
    if (confirm(`Hapus produk "${product.name}"?`)) {
        router.delete(route('products.destroy', product.id));
    }
}

function toggleVisibility(product) {
    router.post(route('products.toggle-visibility', product.id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            const msg = page.props.flash?.success;
            if (msg) showToast(msg);
        },
    });
}

watch(() => page.props.flash?.success, (msg) => {
    if (msg) showToast(msg);
});
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kategori</p>
                </div>
                <p class="text-2xl font-bold text-on-surface">{{ stats.categories }}</p>
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
                placeholder="Cari produk..."
                aria-label="Cari produk"
                class="flex-1 py-3 px-4 rounded-xl border border-outline bg-surface text-on-surface placeholder-on-surface-variant text-sm focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/30 focus-visible:border-primary transition-all"
            />
        </div>

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
                    class="fixed bottom-24 md:bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface font-medium text-sm shadow-xl"
                >
                    {{ toast }}
                </div>
            </Transition>
        </Teleport>

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
