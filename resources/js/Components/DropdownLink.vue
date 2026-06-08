<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-on-surface hover:bg-surface-container focus:outline-none focus:bg-surface-container transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
