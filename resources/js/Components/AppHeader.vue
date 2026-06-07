<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useTheme } from '@/Composables/useTheme';

const props = defineProps({
    headline: {
        type: String,
        required: true,
    },
    showBack: {
        type: Boolean,
        default: false,
    },
    backRoute: {
        type: String,
        default: '/',
    },
    showStoreMenu: {
        type: Boolean,
        default: true,
    },
});

const { isDark, toggleTheme } = useTheme();

const menuOpen = ref(false);

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div v-if="showStoreMenu && menuOpen" class="fixed inset-0 z-40" @click="menuOpen = false"></div>

    <header
        class="fixed top-0 left-0 w-full z-50 bg-surface/80 backdrop-blur px-4 h-16 flex items-center justify-between border-b border-outline"
    >
        <div class="flex items-center gap-3">
            <button
                v-if="showBack"
                :aria-label="'Kembali'"
                class="flex items-center justify-center w-11 h-11 rounded-lg hover:bg-surface-container-high focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent transition-colors"
            >
                <Link :href="backRoute" class="flex items-center justify-center w-full h-full" aria-label="Kembali">
                    <svg class="w-6 h-6 text-on-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
            </button>
            <h1 class="font-bold text-lg text-on-surface">{{ headline }}</h1>
        </div>

        <div class="flex items-center gap-1">
            <button
                @click="toggleTheme"
                :aria-label="'Ganti tema'"
                class="flex items-center justify-center w-11 h-11 rounded-lg hover:bg-surface-container-high focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent transition-colors text-on-surface"
            >
                <svg
                    v-if="isDark"
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg
                    v-else
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>

            <div v-if="showStoreMenu" class="relative">
                <button
                    @click.stop="menuOpen = !menuOpen"
                    aria-label="Menu profil toko"
                    class="flex items-center justify-center w-11 h-11 rounded-lg hover:bg-surface-container-high focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent transition-colors text-on-surface"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </button>
                <Transition
                    enter-active-class="transition-all duration-200"
                    enter-from-class="opacity-0 scale-95 -translate-y-2"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition-all duration-150"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 -translate-y-2"
                >
                    <div
                        v-if="menuOpen"
                        class="absolute top-full right-0 mt-1 w-48 bg-surface-container-low border border-outline rounded-xl shadow-xl overflow-hidden origin-top-right"
                    >
                        <Link
                            :href="route('store-profile.edit')"
                            @click="menuOpen = false"
                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container-high transition-colors"
                        >
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Profil Toko
                        </Link>
                        <hr class="border-outline mx-2" />
                        <button
                            @click="logout"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-400 hover:bg-surface-container-high transition-colors text-left"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </div>
                </Transition>
            </div>
        </div>
    </header>
</template>
