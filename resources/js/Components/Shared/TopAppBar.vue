<script setup>
import { Link } from '@inertiajs/vue3';

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
});

const emit = defineEmits(['toggle-accessibility']);
</script>

<template>
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

        <button
            @click="emit('toggle-accessibility')"
            :aria-label="'Pengaturan aksesibilitas'"
            class="flex items-center justify-center w-11 h-11 rounded-lg hover:bg-surface-container-high focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent transition-colors"
        >
            <svg class="w-6 h-6 text-on-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
        </button>
    </header>
</template>
