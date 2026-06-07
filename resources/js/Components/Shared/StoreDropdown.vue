<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const menuOpen = ref(false);

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div v-if="menuOpen" class="fixed inset-0 z-40" @click="menuOpen = false"></div>

    <div class="fixed top-0 right-0 z-50 flex items-center h-16 px-4">
        <button
            @click.stop="menuOpen = !menuOpen"
            aria-label="Menu profil toko"
            class="flex items-center justify-center w-11 h-11 rounded-lg hover:bg-surface-container-high focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent transition-colors text-on-surface"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
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
                class="absolute top-16 right-4 w-48 bg-surface-container-low border border-outline rounded-xl shadow-xl overflow-hidden origin-top-right"
            >
                <Link
                    :href="route('store-profile.edit')"
                    @click="menuOpen = false"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-on-surface hover:bg-surface-container-high transition-colors"
                >
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Profil Toko
                </Link>
                <hr class="border-outline mx-2" />
                <button
                    @click="logout"
                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-400 hover:bg-surface-container-high transition-colors text-left"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </div>
        </Transition>
    </div>
</template>
