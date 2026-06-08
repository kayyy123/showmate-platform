<script setup>
defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'secondary', 'tertiary', 'success', 'warning', 'danger', 'neutral'].includes(v),
    },
    size: {
        type: String,
        default: 'sm',
    },
});
</script>

<template>
    <span
        class="inline-flex items-center font-bold uppercase leading-none rounded"
        :class="[
            size === 'xs' ? 'text-[10px] px-1.5 py-0.5' : '',
            size === 'sm' ? 'text-xs px-2 py-0.5' : '',
            size === 'md' ? 'text-sm px-2.5 py-1' : '',
            variant === 'primary' ? 'border border-primary text-primary' : '',
            variant === 'secondary' ? 'border border-secondary text-secondary' : '',
            variant === 'tertiary' ? 'border border-tertiary text-tertiary' : '',
            variant === 'success' ? 'border border-emerald-500 text-emerald-500' : '',
            variant === 'warning' ? 'border border-amber-500 text-amber-500' : '',
            variant === 'danger' ? 'border border-red-500 text-red-500' : '',
            variant === 'neutral' ? 'border border-on-surface-variant text-on-surface-variant' : '',
        ]"
    >
        <slot />
    </span>
</template>
