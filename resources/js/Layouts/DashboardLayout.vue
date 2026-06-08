<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useTheme } from '@/Composables/useTheme';
import { useAccessibility } from '@/Composables/useAccessibility';

const props = defineProps({
    activeTab: {
        type: String,
        default: 'manage',
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const storeName = computed(() => user.value?.store_name || 'Toko Saya');
const storeInitial = computed(() => {
    const name = user.value?.store_name || user.value?.name || '';
    return name.charAt(0).toUpperCase() || 'T';
});
const plan = computed(() => user.value?.plan || 'free');
const avatarUrl = computed(() => {
    const u = user.value;
    if (u?.profile_photo_url) return u.profile_photo_url;
    if (u?.profile_photo) return '/storage/' + u.profile_photo;
    if (u?.store_logo) return '/storage/' + u.store_logo;
    if (u?.avatar) return u.avatar;
    return null;
});
const { isDark, toggleTheme } = useTheme();
const { fontSize, highContrast, increaseFont, decreaseFont, toggleHighContrast } = useAccessibility();

const sidebarOpen = ref(false);
const profileMenuOpen = ref(false);
const imgError = ref(false);

watch(avatarUrl, () => { imgError.value = false; });

const navItems = [
    { key: 'manage', label: 'Dashboard', icon: 'dashboard', route: route('merchant.manage') },
    { key: 'catalog', label: 'Katalog Saya', icon: 'link', route: route('merchant.catalog') },
    { key: 'links', label: 'Tautan Saya', icon: 'links', route: route('links.index') },
    { key: 'stats', label: 'Statistik', icon: 'chart', route: route('merchant.stats') },
    { key: 'profile', label: 'Profil Toko', icon: 'store', route: route('store-profile.edit') },
];

const bottomNavTabs = [
    { key: 'links', label: 'Tautan', icon: 'links', route: route('links.index') },
    { key: 'manage', label: 'Dashboard', icon: 'dashboard', route: route('merchant.manage') },
    { key: 'catalog', label: 'Katalog', icon: 'link', route: route('merchant.catalog') },
];

function logout() {
    router.post(route('logout'));
}

function closeSidebar() {
    sidebarOpen.value = false;
}
</script>

<template>
    <div class="min-h-screen bg-surface text-on-surface flex">
        <!-- Mobile overlay -->
        <Transition
            enter-active-class="transition-opacity duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-40 bg-on-surface/50 md:hidden"
                @click="closeSidebar"
                aria-hidden="true"
            />
        </Transition>

        <!-- Sidebar -->
        <Transition
            enter-active-class="transition-transform duration-200"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <aside
                v-if="sidebarOpen"
                class="fixed inset-y-0 left-0 z-50 w-64 bg-surface border-r border-outline flex flex-col md:hidden"
            >
                <div class="flex items-center justify-between h-16 px-4 border-b border-outline">
                    <span class="font-bold text-lg text-on-surface">EtalaseKu</span>
                    <button
                        @click="closeSidebar"
                        class="w-10 h-10 rounded-lg flex items-center justify-center hover:bg-surface-container transition-colors"
                        aria-label="Tutup sidebar"
                    >
                        <svg class="w-5 h-5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            <nav aria-label="Navigasi utama" class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
                    <Link
                        v-for="item in navItems"
                        :key="item.key"
                        :href="item.route"
                        @click="closeSidebar"
                        :aria-current="activeTab === item.key ? 'page' : undefined"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors"
                        :class="activeTab === item.key ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
                    >
                        <svg v-if="item.icon === 'dashboard'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <svg v-else-if="item.icon === 'link'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <svg v-else-if="item.icon === 'links'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <svg v-else-if="item.icon === 'chart'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <svg v-else-if="item.icon === 'store'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        {{ item.label }}
                    </Link>
                </nav>
                <div class="p-3 border-t border-outline space-y-2">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <img
                            v-if="!imgError && avatarUrl"
                            :src="avatarUrl"
                            :alt="'Foto profil ' + storeName"
                            class="w-10 h-10 rounded-full object-cover object-center shrink-0"
                            @error="imgError = true"
                        />
                        <div v-else class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm shrink-0" aria-hidden="true">
                            {{ storeInitial }}
                        </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-on-surface truncate">{{ storeName }}</p>
                        <p class="text-xs text-on-surface-variant truncate">{{ user?.email || '' }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                :class="plan === 'pro'
                                    ? 'bg-yellow-500/10 text-yellow-500 border-yellow-500/30'
                                    : 'bg-surface-container text-on-surface-variant border-outline'"
                            >
                                {{ plan === 'pro' ? 'Pro' : 'Gratis' }}
                            </span>
                            <Link
                                v-if="plan === 'free'"
                                :href="route('upgrade-pro.page')"
                                class="text-[10px] font-semibold text-primary hover:underline"
                            >
                                Upgrade ke Pro
                            </Link>
                        </div>
                    </div>
                </div>
                <button
                    @click="logout"
                    class="flex items-center gap-3 px-3 py-2.5 w-full rounded-xl text-sm font-medium text-red-400 hover:bg-surface-container transition-colors"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </div>
        </aside>
    </Transition>

    <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:flex-col md:w-60 lg:w-64 md:fixed md:inset-y-0 md:border-r md:border-outline md:bg-surface z-30">
            <div class="flex items-center h-16 px-6 border-b border-outline">
                <span class="font-bold text-xl text-on-surface tracking-tight">EtalaseKu</span>
            </div>
            <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
                <Link
                    v-for="item in navItems"
                    :key="item.key"
                    :href="item.route"
                    :aria-current="activeTab === item.key ? 'page' : undefined"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors"
                    :class="activeTab === item.key ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
                >
                    <svg v-if="item.icon === 'dashboard'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <svg v-else-if="item.icon === 'link'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <svg v-else-if="item.icon === 'links'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <svg v-else-if="item.icon === 'chart'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <svg v-else-if="item.icon === 'store'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        {{ item.label }}
                    </Link>
                </nav>
                <div class="p-3 border-t border-outline">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <img
                            v-if="!imgError && avatarUrl"
                            :src="avatarUrl"
                            :alt="'Foto profil ' + storeName"
                            class="w-10 h-10 rounded-full object-cover object-center shrink-0"
                            @error="imgError = true"
                        />
                        <div v-else class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm shrink-0" aria-hidden="true">
                            {{ storeInitial }}
                        </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-on-surface truncate">{{ storeName }}</p>
                        <p class="text-xs text-on-surface-variant truncate">{{ user?.email || '' }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                :class="plan === 'pro'
                                    ? 'bg-yellow-500/10 text-yellow-500 border-yellow-500/30'
                                    : 'bg-surface-container text-on-surface-variant border-outline'"
                            >
                                {{ plan === 'pro' ? 'Pro' : 'Gratis' }}
                            </span>
                            <Link
                                v-if="plan === 'free'"
                                :href="route('upgrade-pro.page')"
                                class="text-[10px] font-semibold text-primary hover:underline"
                            >
                                Upgrade ke Pro
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
    </aside>

        <!-- Main area -->
        <div class="flex-1 flex flex-col md:ml-60 lg:ml-64">
            <!-- Top bar (mobile) -->
            <header class="md:hidden flex items-center justify-between h-16 px-4 border-b border-outline bg-surface/80 backdrop-blur">
                <div class="flex items-center gap-3">
                    <button
                        @click="sidebarOpen = true"
                        class="w-10 h-10 rounded-lg flex items-center justify-center hover:bg-surface-container transition-colors"
                        aria-label="Buka menu"
                    >
                        <svg class="w-6 h-6 text-on-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="font-bold text-lg text-on-surface">EtalaseKu</span>
                </div>
                <div class="flex items-center gap-1">
                    <button
                        @click="toggleTheme"
                        class="w-10 h-10 rounded-lg flex items-center justify-center hover:bg-surface-container transition-colors text-on-surface"
                        :aria-label="isDark ? 'Mode terang' : 'Mode gelap'"
                    >
                        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    <div class="relative">
                        <button
                            @click="profileMenuOpen = !profileMenuOpen"
                            class="w-10 h-10 rounded-full overflow-hidden bg-primary/20 flex items-center justify-center text-primary font-bold text-sm hover:brightness-110 transition-all"
                        >
                            <img
                                v-if="!imgError && avatarUrl"
                                :src="avatarUrl"
                                :alt="'Foto profil ' + storeName"
                                class="w-full h-full object-cover object-center"
                                @error="imgError = true"
                            />
                            <span v-else>{{ storeInitial }}</span>
                        </button>
                        <div v-if="profileMenuOpen" class="fixed inset-0 z-40" @click="profileMenuOpen = false" />
                        <Transition
                            enter-active-class="transition-all duration-200"
                            enter-from-class="opacity-0 scale-95 -translate-y-2"
                            enter-to-class="opacity-100 scale-100 translate-y-0"
                            leave-active-class="transition-all duration-150"
                            leave-from-class="opacity-100 scale-100 translate-y-0"
                            leave-to-class="opacity-0 scale-95 -translate-y-2"
                        >
                            <div
                                v-if="profileMenuOpen"
                                class="absolute right-0 mt-2 w-48 bg-surface border border-outline rounded-xl shadow-xl overflow-hidden origin-top-right z-50"
                            >
                                <Link
                                    :href="route('store-profile.edit')"
                                    @click="profileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors"
                                >
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Profil Toko
                                </Link>
                                <Link
                                    :href="route('profile.edit')"
                                    @click="profileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors"
                                >
                                    <svg class="w-5 h-5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Pengaturan Akun
                                </Link>
                                <hr class="border-outline mx-2" />
                                <button
                                    @click="logout"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-400 hover:bg-surface-container transition-colors text-left"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <!-- Top bar (desktop) -->
            <header class="hidden md:flex items-center justify-between h-16 px-6 border-b border-outline bg-surface/80 backdrop-blur sticky top-0 z-20">
                <div>
                    <h2 class="font-bold text-lg text-on-surface">
                        <slot name="header" />
                    </h2>
                </div>
                <div class="flex items-center gap-1">
                    <span class="hidden sm:flex items-center gap-1 mr-1" role="toolbar" aria-label="Pengaturan aksesibilitas">
                        <button
                            @click="decreaseFont"
                            :disabled="fontSize <= 80"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors disabled:opacity-30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                            aria-label="Perkecil ukuran teks"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                        <span class="text-[11px] font-medium text-on-surface-variant w-6 text-center" aria-live="polite">{{ fontSize }}%</span>
                        <button
                            @click="increaseFont"
                            :disabled="fontSize >= 140"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors disabled:opacity-30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                            aria-label="Perbesar ukuran teks"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <button
                            @click="toggleHighContrast"
                            class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50"
                            :class="highContrast ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'"
                            :aria-label="highContrast ? 'Nonaktifkan mode kontras tinggi' : 'Aktifkan mode kontras tinggi'"
                            :aria-pressed="highContrast"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                    </span>
                    <button
                        @click="toggleTheme"
                        class="w-10 h-10 rounded-lg flex items-center justify-center hover:bg-surface-container transition-colors text-on-surface"
                        :aria-label="isDark ? 'Mode terang' : 'Mode gelap'"
                    >
                        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    <div class="relative">
                        <button
                            @click="profileMenuOpen = !profileMenuOpen"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-surface-container transition-colors"
                        >
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-primary/20 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                                <img
                                    v-if="!imgError && avatarUrl"
                                    :src="avatarUrl"
                                    :alt="'Foto profil ' + storeName"
                                    class="w-full h-full object-cover object-center"
                                    @error="imgError = true"
                                />
                                <span v-else aria-hidden="true">{{ storeInitial }}</span>
                            </div>
                            <div class="text-left hidden lg:block">
                                <p class="text-sm font-medium text-on-surface leading-tight">{{ storeName }}</p>
                                <p class="text-xs text-on-surface-variant leading-tight">{{ user?.email || '' }}</p>
                            </div>
                        </button>
                        <div v-if="profileMenuOpen" class="fixed inset-0 z-40" @click="profileMenuOpen = false" />
                        <Transition
                            enter-active-class="transition-all duration-200"
                            enter-from-class="opacity-0 scale-95 -translate-y-2"
                            enter-to-class="opacity-100 scale-100 translate-y-0"
                            leave-active-class="transition-all duration-150"
                            leave-from-class="opacity-100 scale-100 translate-y-0"
                            leave-to-class="opacity-0 scale-95 -translate-y-2"
                        >
                            <div
                                v-if="profileMenuOpen"
                                class="absolute right-0 mt-2 w-48 bg-surface border border-outline rounded-xl shadow-xl overflow-hidden origin-top-right z-50"
                            >
                                <Link
                                    :href="route('store-profile.edit')"
                                    @click="profileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors"
                                >
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Profil Toko
                                </Link>
                                <Link
                                    :href="route('profile.edit')"
                                    @click="profileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container transition-colors"
                                >
                                    <svg class="w-5 h-5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Pengaturan Akun
                                </Link>
                                <hr class="border-outline mx-2" />
                                <button
                                    @click="logout"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-400 hover:bg-surface-container transition-colors text-left"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1">
                <div class="max-w-5xl mx-auto px-4 py-6 md:px-6 md:py-8">
                    <slot />
                </div>
            </main>

            <!-- Mobile bottom nav -->
            <nav class="md:hidden fixed bottom-0 left-0 w-full z-30 bg-surface border-t border-outline safe-area-bottom" aria-label="Navigasi bawah">
                <div class="flex items-center justify-around h-16 max-w-lg mx-auto">
                    <Link
                        v-for="tab in bottomNavTabs"
                        :key="tab.key"
                        :href="tab.route"
                        :aria-label="tab.label"
                        :aria-current="activeTab === tab.key ? 'page' : undefined"
                        class="flex flex-col items-center justify-center w-20 h-full gap-0.5 transition-colors"
                        :class="activeTab === tab.key ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface'"
                    >
                        <svg v-if="tab.icon === 'link'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <svg v-else-if="tab.icon === 'links'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <svg v-else-if="tab.icon === 'dashboard'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <svg v-else-if="tab.icon === 'chart'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-[10px] font-semibold uppercase tracking-wider">{{ tab.label }}</span>
                    </Link>
                </div>
            </nav>
        </div>

        <!-- Bottom padding for mobile nav -->
        <div class="md:hidden h-16" />
    </div>
</template>
