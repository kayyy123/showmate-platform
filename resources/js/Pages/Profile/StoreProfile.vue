<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const CATALOG_THEME_KEY = 'showmate-catalog-theme';

const props = defineProps({
    store: Object,
});

const page = usePage();
const userSlug = page.props.auth.user.slug;
const catalogUrl = `/catalog/${userSlug}`;

const previewLogo = ref(props.store.store_logo_url || null);

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

const themePresets = [
    { id: 'dark-gold', label: 'Dark Gold', bg: 'bg-gray-950', text: 'text-white', accent: 'bg-amber-400', accentText: 'text-gray-950', surface: 'bg-gray-800', border: 'border-gray-700', muted: 'text-gray-400' },
    { id: 'dark-blue', label: 'Dark Ocean', bg: 'bg-gray-950', text: 'text-white', accent: 'bg-cyan-400', accentText: 'text-gray-950', surface: 'bg-gray-800', border: 'border-gray-700', muted: 'text-gray-400' },
    { id: 'light-blue', label: 'Light Sky', bg: 'bg-white', text: 'text-gray-900', accent: 'bg-blue-600', accentText: 'text-white', surface: 'bg-gray-50', border: 'border-gray-200', muted: 'text-gray-500' },
    { id: 'light-green', label: 'Light Earth', bg: 'bg-white', text: 'text-gray-900', accent: 'bg-emerald-600', accentText: 'text-white', surface: 'bg-gray-50', border: 'border-gray-200', muted: 'text-gray-500' },
];

function getStoredTheme() {
    const stored = localStorage.getItem(CATALOG_THEME_KEY);
    if (stored) {
        const found = themePresets.find(t => t.id === stored);
        if (found) return found;
    }
    return themePresets[0];
}

const selectedTheme = ref(getStoredTheme());

function selectTheme(theme) {
    selectedTheme.value = theme;
    localStorage.setItem(CATALOG_THEME_KEY, theme.id);
}

const previewChannels = computed(() => {
    const items = [];
    if (form.whatsapp_number) items.push('WhatsApp');
    if (form.instagram_url) items.push('Instagram');
    if (form.tiktok_url) items.push('TikTok');
    if (form.shopee_url) items.push('Shopee');
    if (form.tokopedia_url) items.push('Tokopedia');
    return items;
});

function onLogoChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.store_logo = file;
        const reader = new FileReader();
        reader.onload = (ev) => { previewLogo.value = ev.target.result; };
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
    <Head title="Profil UMKM" />

    <DashboardLayout activeTab="profile">
        <template #header>Profil UMKM</template>

        <div class="space-y-6 max-w-3xl">

            <!-- Banner Preview -->
            <section>
                <div
                    class="relative w-full h-40 md:h-48 rounded-2xl overflow-hidden"
                    :class="selectedTheme.bg"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/40 via-secondary/20 to-tertiary/30" />
                    <div
                        class="absolute inset-0 opacity-20"
                        :class="selectedTheme.accent"
                        style="mask-image: radial-gradient(circle at 70% 30%, black 0%, transparent 70%); -webkit-mask-image: radial-gradient(circle at 70% 30%, black 0%, transparent 70%);"
                    />
                    <div class="absolute inset-0 flex items-center gap-4 px-6">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-white/30 overflow-hidden shrink-0 shadow-lg">
                            <img
                                v-if="previewLogo"
                                :src="previewLogo"
                                alt="Logo"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center" :class="selectedTheme.surface">
                                <svg class="w-8 h-8" :class="selectedTheme.muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-xl md:text-2xl font-bold drop-shadow-sm" :class="selectedTheme.text">
                                {{ form.store_name || 'Nama UMKM' }}
                            </h2>
                            <p v-if="form.store_description" class="text-sm mt-1 line-clamp-2 opacity-80" :class="selectedTheme.text">
                                {{ form.store_description }}
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Banner pratinjau tampilan publik — warna menyesuaikan tema yang dipilih di bagian Tampilan Publik
                </p>
            </section>

            <!-- Branding -->
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h2 class="font-bold text-lg text-on-surface">Branding UMKM</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5 space-y-5">
                    <!-- Logo -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-3">Logo UMKM</label>
                        <div class="flex items-center gap-5">
                            <div class="relative w-28 h-28 rounded-2xl bg-surface-container-low border-2 border-dashed border-outline flex items-center justify-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all overflow-hidden shrink-0 group">
                                <input
                                    type="file"
                                    accept="image/*"
                                    class="absolute inset-0 opacity-0 cursor-pointer"
                                    @change="onLogoChange"
                                    aria-label="Upload logo UMKM"
                                />
                                <img
                                    v-if="previewLogo"
                                    :src="previewLogo"
                                    alt="Preview logo"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                <div v-else class="flex flex-col items-center gap-1">
                                    <svg class="w-8 h-8 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] text-on-surface-variant font-medium">Upload Logo</span>
                                </div>
                                <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/5 transition-colors rounded-2xl" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-on-surface-variant">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                                <p class="text-xs text-on-surface-variant mt-1">Ukuran ideal: 512x512px (persegi).</p>
                                <p v-if="form.errors.store_logo" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.store_logo }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nama UMKM -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="store_name">Nama UMKM</label>
                        <input
                            id="store_name"
                            v-model="form.store_name"
                            type="text"
                            placeholder="Nama toko atau usaha Anda"
                            class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                        />
                        <p v-if="form.errors.store_name" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.store_name }}</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="store_description">Deskripsi Usaha</label>
                        <textarea
                            id="store_description"
                            v-model="form.store_description"
                            rows="4"
                            placeholder="Ceritakan tentang usaha Anda..."
                            class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors resize-none"
                        ></textarea>
                        <div class="flex justify-between mt-1">
                            <p v-if="form.errors.store_description" class="text-sm text-red-400" role="alert">{{ form.errors.store_description }}</p>
                            <p v-else class="text-xs text-on-surface-variant">Maks 2.000 karakter</p>
                            <p class="text-xs text-on-surface-variant">{{ form.store_description.length }}/2000</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Kontak & Media Sosial -->
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    <h2 class="font-bold text-lg text-on-surface">Kontak & Media Sosial</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5 space-y-4">
                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="whatsapp_number">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                WhatsApp
                            </span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-on-surface-variant text-sm font-mono pointer-events-none">+</span>
                            <input
                                id="whatsapp_number"
                                v-model="form.whatsapp_number"
                                type="text"
                                placeholder="6281234567890"
                                class="w-full pl-8 pr-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                            />
                        </div>
                        <p v-if="form.errors.whatsapp_number" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.whatsapp_number }}</p>
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="instagram_url">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-pink-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                </svg>
                                Instagram
                            </span>
                        </label>
                        <input
                            id="instagram_url"
                            v-model="form.instagram_url"
                            type="url"
                            placeholder="https://instagram.com/username"
                            class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                        />
                        <p v-if="form.errors.instagram_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.instagram_url }}</p>
                    </div>

                    <!-- TikTok -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="tiktok_url">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-900 dark:text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                </svg>
                                TikTok
                            </span>
                        </label>
                        <input
                            id="tiktok_url"
                            v-model="form.tiktok_url"
                            type="url"
                            placeholder="https://tiktok.com/@username"
                            class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                        />
                        <p v-if="form.errors.tiktok_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.tiktok_url }}</p>
                    </div>
                </div>
            </section>

            <!-- Marketplace -->
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h2 class="font-bold text-lg text-on-surface">Marketplace</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5 space-y-4">
                    <!-- Shopee -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="shopee_url">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-orange-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.912 2.904c.103.003.206.008.308.017 2.21.18 4.042 1.557 4.904 3.489.327.732.477 1.467.494 2.201.02.852-.126 1.598-.42 2.28-.138.319-.404.576-.597.857a.963.963 0 01-.12.139c-.488.487-1.046.8-1.677.982-.625.18-1.27.23-1.905.107-.554-.107-1.064-.334-1.472-.718-.238-.225-.428-.49-.55-.795a5.27 5.27 0 01-.218-.541c-.146-.43-.457-.657-.892-.66-.528-.003-.905.27-1.067.775l-.003.012c-.145.448-.159.895-.002 1.343.117.334.303.611.59.832.632.487 1.38.699 2.148.714.588.012 1.158-.079 1.715-.278.537-.192 1.043-.457 1.501-.8.144-.108.278-.23.41-.353v.006c.18.111.343.24.483.395.567.627.836 1.366.877 2.218.044.922-.13 1.792-.574 2.58-.375.666-.906 1.18-1.553 1.575-.745.455-1.571.685-2.454.721-.855.035-1.677-.09-2.455-.402-.598-.24-1.116-.59-1.535-1.078-.375-.437-.63-.946-.775-1.518-.158-.625-.127-1.25-.018-1.88.174-1.002.63-1.857 1.3-2.58.664-.718 1.487-1.193 2.44-1.465.07-.02.141-.04.212-.057-.136-.096-.25-.216-.355-.347-.63-.79-.862-1.699-.701-2.699.178-1.106.771-1.98 1.66-2.66.315-.24.667-.418 1.047-.532.33-.1.668-.144 1.01-.137zm.177 1.386c-.28-.009-.56.022-.834.09a3.815 3.815 0 00-1.256.566c-.396.284-.737.617-1.01 1.014-.269.39-.46.81-.576 1.27-.116.462-.103.903.02 1.336.134.472.375.874.727 1.199.153.142.325.253.516.338.012.009.023.019.035.028.13.108.26.216.39.324l-.08.024c-.555.173-1.074.418-1.536.752-.507.367-.931.794-1.245 1.326-.313.53-.506 1.105-.552 1.728-.041.55.006 1.091.155 1.61.15.524.392.992.739 1.402.362.428.802.754 1.302.982.521.236 1.068.386 1.635.447.582.062 1.158.019 1.72-.138.562-.157 1.076-.416 1.524-.785.434-.358.79-.781 1.053-1.27.275-.511.414-1.062.431-1.637.016-.552-.063-1.087-.26-1.597a3.332 3.332 0 00-.447-.832c-.083-.11-.175-.21-.278-.302a1.747 1.747 0 00-.654-.397.373.373 0 01-.248-.296.395.395 0 01.148-.379c.297-.23.58-.474.827-.755.38-.43.646-.932.797-1.494.134-.499.148-1.006.015-1.506-.134-.504-.361-.95-.699-1.33-.351-.396-.774-.688-1.265-.891a3.378 3.378 0 00-.671-.218 5.17 5.17 0 00-.707-.155z"/>
                                </svg>
                                Shopee
                            </span>
                        </label>
                        <input
                            id="shopee_url"
                            v-model="form.shopee_url"
                            type="url"
                            placeholder="https://shopee.co.id/username"
                            class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                        />
                        <p v-if="form.errors.shopee_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.shopee_url }}</p>
                    </div>

                    <!-- Tokopedia -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2" for="tokopedia_url">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5.763 17.487c.003-.05.003-.1.004-.149.008-.828.012-1.659.022-2.487.005-.397-.023-.656-.377-.88-.663-.421-1.324-.846-1.987-1.267-.36-.228-.542-.547-.54-.99.002-.454.178-.78.545-1.01.658-.411 1.321-.814 1.982-1.22.336-.207.372-.486.37-.842-.011-1.101-.005-2.202-.005-3.303H5.761c-.159 0-.315-.013-.466.024-.728.179-1.312-.08-1.59-.759a1.36 1.36 0 01.136-1.282c.243-.37.6-.542 1.03-.523.422.018.836.14 1.215.330.206.104.32.065.433-.112.187-.292.378-.581.567-.872.004-.006.009-.011.014-.017h5.362c.005.006.01.011.014.017.189.291.38.58.567.872.113.177.227.216.433.112.379-.19.793-.312 1.215-.33.43-.019.787.153 1.03.523.238.361.282.792.136 1.282-.278.68-.862.938-1.59.759-.15-.037-.307-.024-.466-.024h-.002c-.002 0-.004.002-.007.005v4.678c-.003.393-.06.737-.455.97-.672.395-1.354.774-2.031 1.16-.348.2-.54.54-.532.937.01.582.004 1.164.004 1.746v2.594c.002.15.014.298.04.444.097.547.024.905-.48 1.207-.294.176-.607.282-.95.289-.407.008-.73-.132-1.007-.43-.301-.324-.385-.72-.237-1.138a1.611 1.611 0 01.275-.487c.207-.254.198-.537.194-.833-.003-.392-.005-.784.003-1.176.001-.061.008-.122.012-.182zm2.175-8.787c0 .333.004.665 0 .997-.002.197.05.29.244.388.787.398 1.573.797 2.362 1.192.16.08.244.064.402-.016.788-.4 1.576-.798 2.362-1.199.19-.097.232-.189.23-.383-.01-.66-.005-1.323-.005-1.998l-2.898 1.706-2.697-1.593v.906z"/>
                                </svg>
                                Tokopedia
                            </span>
                        </label>
                        <input
                            id="tokopedia_url"
                            v-model="form.tokopedia_url"
                            type="url"
                            placeholder="https://tokopedia.com/username"
                            class="w-full px-4 py-3 rounded-xl bg-surface-container border border-outline text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                        />
                        <p v-if="form.errors.tokopedia_url" class="mt-1 text-sm text-red-400" role="alert">{{ form.errors.tokopedia_url }}</p>
                    </div>
                </div>
            </section>

            <!-- Tampilan Publik -->
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <h2 class="font-bold text-lg text-on-surface">Tampilan Halaman Publik</h2>
                </div>
                <div class="rounded-2xl border border-outline bg-surface p-5 space-y-4">
                    <p class="text-sm text-on-surface-variant">Pilih skema warna untuk halaman katalog publik Anda. Perubahan tersimpan di perangkat ini.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button
                            v-for="theme in themePresets"
                            :key="theme.id"
                            @click="selectTheme(theme)"
                            class="relative rounded-xl border-2 p-3 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                            :class="selectedTheme.id === theme.id ? 'border-primary ring-1 ring-primary/30' : 'border-outline hover:border-primary/50'"
                        >
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold" :class="theme.accent + ' ' + theme.accentText">A</div>
                                <div class="flex-1 h-2 rounded" :class="theme.surface"></div>
                            </div>
                            <div class="flex gap-1 mb-1">
                                <div class="w-3 h-3 rounded-full" :class="theme.accent"></div>
                                <div class="w-3 h-3 rounded-full" :class="theme.surface"></div>
                                <div class="w-3 h-3 rounded-full" :class="theme.bg"></div>
                            </div>
                            <span class="text-xs font-medium" :class="selectedTheme.id === theme.id ? 'text-primary' : 'text-on-surface-variant'">{{ theme.label }}</span>
                            <div
                                v-if="selectedTheme.id === theme.id"
                                class="absolute -top-2 -right-2 w-5 h-5 rounded-full bg-primary text-on-primary flex items-center justify-center"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Pratinjau Publik -->
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <h2 class="font-bold text-lg text-on-surface">Pratinjau Halaman Publik</h2>
                    </div>
                    <a
                        :href="catalogUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary/80 transition-colors"
                    >
                        Buka halaman
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                <div
                    class="rounded-2xl overflow-hidden border-2 shadow-xl"
                    :class="[selectedTheme.border, selectedTheme.bg]"
                >
                    <!-- Preview header -->
                    <div class="p-6 text-center border-b" :class="[selectedTheme.border, selectedTheme.bg]">
                        <div class="w-20 h-20 rounded-full mx-auto mb-3 border-4 overflow-hidden" :class="selectedTheme.border.replace('border-', 'border-')">
                            <img
                                v-if="previewLogo"
                                :src="previewLogo"
                                alt="Logo"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center" :class="selectedTheme.surface">
                                <svg class="w-8 h-8" :class="selectedTheme.muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold" :class="selectedTheme.text">
                            {{ form.store_name || 'Nama UMKM' }}
                        </h3>
                        <p v-if="form.store_description" class="text-sm mt-1" :class="selectedTheme.muted">
                            {{ form.store_description }}
                        </p>
                    </div>

                    <!-- Preview social channels -->
                    <div v-if="previewChannels.length" class="p-4 flex flex-wrap gap-2 justify-center" :class="selectedTheme.bg">
                        <span
                            v-for="ch in previewChannels"
                            :key="ch"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium"
                            :class="selectedTheme.surface + ' ' + selectedTheme.text + ' ' + selectedTheme.border"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            {{ ch }}
                        </span>
                    </div>

                    <!-- Preview product placeholders -->
                    <div class="p-4 space-y-2" :class="selectedTheme.bg">
                        <div v-for="i in 3" :key="i" class="flex items-center gap-3 p-3 rounded-xl" :class="selectedTheme.surface">
                            <div class="w-12 h-12 rounded-lg" :class="selectedTheme.border.replace('border-', 'bg-') + '/30'"></div>
                            <div class="flex-1 space-y-1.5">
                                <div class="h-3 w-3/4 rounded" :class="selectedTheme.bg"></div>
                                <div class="h-2 w-1/3 rounded" :class="selectedTheme.bg"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview copy link button -->
                    <div class="p-4 pt-0" :class="selectedTheme.bg">
                        <div
                            class="w-full py-2.5 rounded-xl text-sm font-bold text-center"
                            :class="selectedTheme.accent + ' ' + selectedTheme.accentText"
                        >
                            Bagikan Katalog
                        </div>
                    </div>
                </div>
            </section>

            <!-- Actions -->
            <div class="flex gap-3 pt-2 pb-6">
                <Link
                    :href="route('merchant.manage')"
                    class="flex-1 px-4 py-3 rounded-xl border border-outline text-on-surface font-medium text-center hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    Batal
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    @click="submit"
                    class="flex-1 px-4 py-3 rounded-xl bg-primary text-on-primary font-bold flex items-center justify-center gap-2 hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                </button>
            </div>
        </div>
    </DashboardLayout>
</template>
