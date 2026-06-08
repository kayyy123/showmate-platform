<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const form = useForm({
    name: '',
    description: '',
    price: '',
    category: '',
    tag: '',
    alt_text: '',
    image: null,
});

const previewUrl = ref(null);

function onImageChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (event) => {
            previewUrl.value = event.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function submit() {
    form.post(route('products.store'), {
        onSuccess: () => form.reset(),
    });
}

function formatPriceInput(e) {
    const val = e.target.value.replace(/\D/g, '');
    form.price = val;
}
</script>

<template>
    <Head title="Tambah Produk" />

    <DashboardLayout activeTab="manage">
        <template #header>Tambah Produk</template>

        <div class="max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6" novalidate>

                <!-- Informasi Produk -->
                <section aria-labelledby="product-info-heading">
                    <h2 id="product-info-heading" class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Informasi Produk
                    </h2>
                    <div class="rounded-2xl border border-outline bg-surface p-5 space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="name">
                                Nama Produk <span class="text-red-400">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Contoh: Tas Rajut Handmade"
                                class="w-full h-12 bg-surface-container border border-outline rounded-xl px-4 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.name }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="description">
                                Deskripsi Produk
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Jelaskan detail produk Anda..."
                                rows="4"
                                class="w-full bg-surface-container border border-outline rounded-xl p-4 text-on-surface placeholder:text-on-surface-variant resize-none focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="price">
                                    Harga (Rp) <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm font-medium">Rp</span>
                                    <input
                                        id="price"
                                        :value="form.price"
                                        @input="formatPriceInput"
                                        type="text"
                                        inputmode="numeric"
                                        required
                                        placeholder="0"
                                        class="w-full h-12 bg-surface-container border border-outline rounded-xl pl-10 pr-4 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                                    />
                                </div>
                                <p v-if="form.errors.price" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.price }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="category">
                                    Kategori
                                </label>
                                <input
                                    id="category"
                                    v-model="form.category"
                                    type="text"
                                    placeholder="Contoh: Fashion"
                                    class="w-full h-12 bg-surface-container border border-outline rounded-xl px-4 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="tag">
                                    Tag
                                </label>
                                <input
                                    id="tag"
                                    v-model="form.tag"
                                    type="text"
                                    placeholder="Contoh: Best Seller"
                                    class="w-full h-12 bg-surface-container border border-outline rounded-xl px-4 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                                />
                                <p class="text-xs text-on-surface-variant mt-1">Muncul sebagai badge di katalog</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="alt_text">
                                    Alt Text Gambar
                                </label>
                                <input
                                    id="alt_text"
                                    v-model="form.alt_text"
                                    type="text"
                                    placeholder="Deskripsi gambar (aksesibilitas)"
                                    class="w-full h-12 bg-surface-container border border-outline rounded-xl px-4 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"
                                />
                                <p class="text-xs text-on-surface-variant mt-1">Untuk pengguna screen reader</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Gambar Produk -->
                <section aria-labelledby="product-image-heading">
                    <h2 id="product-image-heading" class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Gambar Produk
                    </h2>
                    <div class="rounded-2xl border border-outline bg-surface p-5">
                        <div
                            class="relative w-full aspect-video bg-surface-container border-2 border-dashed border-outline rounded-xl flex flex-col items-center justify-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all group overflow-hidden"
                        >
                            <input
                                type="file"
                                accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                @change="onImageChange"
                                aria-label="Upload gambar produk"
                            />
                            <div v-if="!previewUrl" class="text-center p-6">
                                <svg class="w-12 h-12 text-on-surface-variant mx-auto mb-3 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-sm text-on-surface-variant font-medium">Tap untuk upload gambar</p>
                                <p class="text-xs text-on-surface-variant mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                            </div>
                            <img
                                v-else
                                :src="previewUrl"
                                :alt="form.alt_text || 'Preview gambar produk'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                        <p v-if="form.errors.image" class="mt-2 text-sm text-red-400" role="alert">{{ form.errors.image }}</p>
                    </div>
                </section>

                <!-- Success -->
                <div
                    v-if="form.recentlySuccessful"
                    class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-4 py-3 text-sm font-medium flex items-center gap-2"
                    role="status"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Produk berhasil ditambahkan!
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2 pb-6">
                    <Link
                        :href="route('merchant.manage')"
                        class="flex-1 h-12 rounded-xl border border-outline text-on-surface font-bold text-sm flex items-center justify-center hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex-1 h-12 rounded-xl bg-primary text-on-primary font-bold text-sm flex items-center justify-center gap-2 hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 disabled:opacity-50"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Produk' }}
                    </button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
