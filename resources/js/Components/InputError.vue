<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="mt-1 text-sm text-red-400" role="alert">
            {{ message }}
        </p>
    </div>
</template>
