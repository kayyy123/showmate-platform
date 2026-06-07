<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import BottomNavBar from '@/Components/Shared/BottomNavBar.vue';

const props = defineProps({
    store: Object,
});

const catalogUrl = `/catalog/${usePage().props.auth.user.slug}`;

const previewUrl = ref(props.store.store_logo_url || null);

const form = useForm({
    store_name: props.store.store_name || '',
    store_description: props.store.store_description || '',
    whatsapp_number: props.store.whatsapp_number || '',
    instagram_url: props.store.instagram_url || '',
    tiktok_url: props.store.tiktok_url || '',
    shopee_url: props.store.shopee_url || '',
    tokopedia_url: props.store.tokopedia_url || '',
    store_logo: null,
});

function onLogoChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.store_logo = file;
        const reader = new FileReader();
        reader.onload = (ev) => { previewUrl.value = ev.target.result; };
        reader.readAsDataURL(file);
    }
}

function submit() {
    form.transform(data => ({
        ...data,
        _method: 'PUT',
    })).post(route('store-profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.defaults().store_logo = null;
        },
    });
}
</script>

<template>
    <Head title="Profil Toko" />

    <div class="min-h-screen bg-surface text-on-surface">
        <AppHeader headline="Profil Toko" :showBack="true" backRoute="/dashboard" />

        <main class="pt-20 pb-24 px-4 max-w-[420px] mx-auto">
            <form @submit.prevent="submit" class="space-y-8">
                <!-- Informasi Toko -->
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h2 class="font-header text-lg text-on-surface">Informasi Toko</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="store_name">Nama Toko</label>
                            <input
                                id="store_name"
                                v-model="form.store_name"
                                type="text"
                                placeholder="Nama toko Anda"
                                class="w-full px-4 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            />
                            <p v-if="form.errors.store_name" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.store_name }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="store_description">Deskripsi</label>
                            <textarea
                                id="store_description"
                                v-model="form.store_description"
                                rows="4"
                                placeholder="Deskripsi toko Anda"
                                class="w-full px-4 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors resize-none"
                            ></textarea>
                            <p v-if="form.errors.store_description" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.store_description }}</p>
                        </div>
                    </div>
                </section>

                <hr class="border-outline" />

                <!-- Kanal Bisnis -->
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <h2 class="font-header text-lg text-on-surface">Kanal Bisnis</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="whatsapp_number">WhatsApp</label>
                            <input
                                id="whatsapp_number"
                                v-model="form.whatsapp_number"
                                type="text"
                                placeholder="6281234567890"
                                class="w-full px-4 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            />
                            <p v-if="form.errors.whatsapp_number" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.whatsapp_number }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="instagram_url">Instagram</label>
                            <input
                                id="instagram_url"
                                v-model="form.instagram_url"
                                type="url"
                                placeholder="https://instagram.com/username"
                                class="w-full px-4 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            />
                            <p v-if="form.errors.instagram_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.instagram_url }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="tiktok_url">TikTok</label>
                            <input
                                id="tiktok_url"
                                v-model="form.tiktok_url"
                                type="url"
                                placeholder="https://tiktok.com/@username"
                                class="w-full px-4 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            />
                            <p v-if="form.errors.tiktok_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.tiktok_url }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="shopee_url">Shopee</label>
                            <input
                                id="shopee_url"
                                v-model="form.shopee_url"
                                type="url"
                                placeholder="https://shopee.co.id/username"
                                class="w-full px-4 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            />
                            <p v-if="form.errors.shopee_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.shopee_url }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="tokopedia_url">Tokopedia</label>
                            <input
                                id="tokopedia_url"
                                v-model="form.tokopedia_url"
                                type="url"
                                placeholder="https://tokopedia.com/username"
                                class="w-full px-4 py-3 rounded-xl bg-surface-container-high border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            />
                            <p v-if="form.errors.tokopedia_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.tokopedia_url }}</p>
                        </div>
                    </div>
                </section>

                <hr class="border-outline" />

                <!-- Branding -->
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h2 class="font-header text-lg text-on-surface">Branding</h2>
                    </div>
                    <div class="text-center">
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-3">
                            Logo Toko
                        </label>
                        <div class="relative w-40 h-40 rounded-full bg-surface-container-low border-2 border-dashed border-outline flex items-center justify-center cursor-pointer hover:border-accent transition-colors overflow-hidden mx-auto">
                            <input
                                type="file"
                                accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                @change="onLogoChange"
                                aria-label="Upload logo toko"
                            />
                            <img
                                v-if="previewUrl"
                                :src="previewUrl"
                                alt="Preview logo toko"
                                class="w-full h-full object-cover"
                            />
                            <svg
                                v-else
                                class="w-12 h-12 text-on-surface-variant"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs text-on-surface-variant mt-2">Tap untuk upload logo</p>
                        <p v-if="form.errors.store_logo" class="mt-1 text-sm text-red-400 text-center" role="alert">{{ form.errors.store_logo }}</p>
                    </div>
                </section>

                <div class="flex gap-3 pt-2">
                    <Link
                        :href="route('dashboard')"
                        class="flex-1 px-4 py-3 rounded-xl border border-outline text-on-surface font-medium text-center hover:bg-surface-container-high transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/50"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex-1 px-4 py-3 rounded-xl bg-accent text-on-accent font-bold flex items-center justify-center gap-2 hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/50"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </main>

        <BottomNavBar active="manage" :catalogUrl="catalogUrl" />
    </div>
</template>
