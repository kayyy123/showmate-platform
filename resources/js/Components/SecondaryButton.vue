<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md',
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="inline-flex items-center justify-center font-bold rounded-xl border transition-all focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-primary/30 disabled:opacity-50 disabled:pointer-events-none"
        :class="[
            size === 'sm' ? 'px-3 py-1.5 text-xs gap-1.5' : '',
            size === 'md' ? 'px-5 py-2.5 text-sm gap-2' : '',
            size === 'lg' ? 'px-6 py-3 text-base gap-2' : '',
            'border-outline text-on-surface bg-surface hover:bg-surface-container active:scale-[0.98]',
        ]"
    >
        <slot />
    </button>
</template>
