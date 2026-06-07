<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import BottomNavBar from '@/Components/Shared/BottomNavBar.vue';

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
</script>

<template>
    <Head title="Tambah Produk" />

    <div class="min-h-screen bg-surface text-on-surface">
        <AppHeader headline="Tambah Produk" :showBack="true" backRoute="/dashboard" />

        <main class="pt-20 pb-24 px-5 max-w-[420px] mx-auto">
            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="name">
                        Nama Produk
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="e.g. Handmade Leather Tote"
                        class="w-full h-12 bg-surface-container border border-outline rounded-lg px-4 text-on-surface placeholder-on-surface-variant focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="price">
                        Harga (Rp)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">Rp</span>
                        <input
                            id="price"
                            v-model="form.price"
                            type="number"
                            placeholder="0"
                            min="0"
                            class="w-full h-12 bg-surface-container border border-outline rounded-lg pl-10 pr-4 text-on-surface placeholder-on-surface-variant focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
                        />
                    </div>
                    <p v-if="form.errors.price" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.price }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="category">
                        Kategori
                    </label>
                    <input
                        id="category"
                        v-model="form.category"
                        type="text"
                        placeholder="e.g. Fashion, Food, Elektronik"
                        class="w-full h-12 bg-surface-container border border-outline rounded-lg px-4 text-on-surface placeholder-on-surface-variant focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="tag">
                        Tag
                    </label>
                    <input
                        id="tag"
                        v-model="form.tag"
                        type="text"
                        placeholder="e.g. Best Seller, New, Limited"
                        class="w-full h-12 bg-surface-container border border-outline rounded-lg px-4 text-on-surface placeholder-on-surface-variant focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="alt_text">
                        Alt Text Gambar
                    </label>
                    <input
                        id="alt_text"
                        v-model="form.alt_text"
                        type="text"
                        placeholder="Deskripsi singkat gambar untuk aksesibilitas"
                        class="w-full h-12 bg-surface-container border border-outline rounded-lg px-4 text-on-surface placeholder-on-surface-variant focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
                    />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="description">
                        Deskripsi
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Deskripsikan produk Anda..."
                        rows="4"
                        class="w-full bg-surface-container border border-outline rounded-lg p-4 text-on-surface placeholder-on-surface-variant resize-none focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface-variant uppercase tracking-wider mb-2">
                        Gambar Produk
                    </label>
                    <div
                        class="relative w-full aspect-video bg-surface-container border-2 border-dashed border-outline rounded-xl flex flex-col items-center justify-center cursor-pointer hover:border-accent transition-colors group overflow-hidden"
                    >
                        <input
                            type="file"
                            accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer"
                            @change="onImageChange"
                            aria-label="Upload gambar produk"
                        />
                        <div v-if="!previewUrl" class="text-center p-6">
                            <svg class="w-10 h-10 text-on-surface-variant mx-auto mb-2 group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-sm text-on-surface-variant">Tap untuk upload gambar</p>
                        </div>
                        <img
                            v-else
                            :src="previewUrl"
                            :alt="form.alt_text || 'Preview gambar produk'"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <p v-if="form.errors.image" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.image }}</p>
                </div>

                <div v-if="form.recentlySuccessful" class="bg-accent/10 border border-accent rounded-lg p-4 text-center" role="status">
                    <p class="text-accent font-bold">Produk berhasil ditambahkan!</p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full h-12 bg-accent text-on-accent rounded-lg font-bold uppercase tracking-wider hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-accent/30 disabled:opacity-50 disabled:grayscale"
                >
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Produk' }}
                </button>
            </form>
        </main>

        <BottomNavBar active="manage" />
    </div>
</template>
