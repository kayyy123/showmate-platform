<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: {
        type: String,
        default: 'catalog',
        validator: (value) => ['catalog', 'manage', 'stats'].includes(value),
    },
});

const tabs = [
    { key: 'catalog', label: 'Katalog', icon: 'grid', route: '/merchant/catalog' },
    { key: 'manage', label: 'Kelola', icon: 'settings', route: '/merchant/manage' },
    { key: 'stats', label: 'Statistik', icon: 'chart', route: '/merchant/stats' },
];
</script>

<template>
    <nav
        class="fixed bottom-0 left-0 w-full z-50 bg-surface border-t border-outline safe-area-bottom"
        aria-label="Navigasi bawah"
    >
        <div class="flex items-center justify-around h-16 max-w-lg mx-auto">
            <Link
                v-for="tab in tabs"
                :key="tab.key"
                :href="tab.route"
                :aria-label="tab.label"
                :aria-current="active === tab.key ? 'page' : undefined"
                class="flex flex-col items-center justify-center w-20 h-full gap-1 rounded-lg transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                :class="active === tab.key ? 'text-accent' : 'text-on-surface-variant hover:text-on-surface'"
            >
                <svg v-if="tab.icon === 'grid'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <svg v-else-if="tab.icon === 'settings'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg v-else-if="tab.icon === 'chart'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-xs font-semibold uppercase tracking-wider">{{ tab.label }}</span>
            </Link>
        </div>
    </nav>
</template>
