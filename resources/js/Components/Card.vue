<script setup>
defineProps({
    variant: {
        type: String,
        default: 'outlined',
        validator: (v) => ['outlined', 'elevated', 'filled'].includes(v),
    },
    padding: {
        type: String,
        default: 'md',
    },
});
</script>

<template>
    <div
        class="rounded-2xl transition-colors"
        :class="[
            variant === 'outlined' ? 'border border-outline bg-surface' : '',
            variant === 'elevated' ? 'bg-surface shadow-lg shadow-on-surface/5' : '',
            variant === 'filled' ? 'bg-surface-container' : '',
            padding === 'none' ? '' : '',
            padding === 'sm' ? 'p-3' : '',
            padding === 'md' ? 'p-4' : '',
            padding === 'lg' ? 'p-6' : '',
        ]"
    >
        <slot />
    </div>
</template>
