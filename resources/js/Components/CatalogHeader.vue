<script setup>
defineProps({
    storeName: {
        type: String,
        required: true,
    },
    storeDescription: {
        type: String,
        default: '',
    },
    storeLogo: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <section class="text-center mb-8">
        <div class="relative inline-block mb-4">
            <div
                class="w-24 h-24 rounded-full border-4 border-accent p-1"
            >
                <img
                    v-if="storeLogo"
                    :src="storeLogo"
                    :alt="storeName"
                    class="w-full h-full rounded-full object-cover"
                />
                <div
                    v-else
                    class="w-full h-full rounded-full bg-surface-container-high flex items-center justify-center"
                    aria-hidden="true"
                >
                    <svg class="w-10 h-10 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
        <h1 class="text-3xl font-bold font-header text-on-surface">{{ storeName }}</h1>
        <p
            v-if="storeDescription"
            class="mt-2 text-on-surface-variant max-w-lg mx-auto"
        >
            {{ storeDescription }}
        </p>
    </section>
</template>
